<?php declare(strict_types=1);
if (!defined('MW_PATH')) {
    exit('No direct script access allowed');
}

/**
 * EmailVerificationExtZerobounceCommon
 *
 * @package MailWizz EMA
 * @author MailWizz Development Team <support@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 2.0.0
 */
class EmailVerificationExtZerobounceCommon extends EmailVerificationExtBaseCommon
{
    /**
     * @var string
     */
    public $api_key = '';

    /**
     * @return array
     * @throws CException
     */
    public function rules()
    {
        $rules = [
            ['api_key', 'safe'],
        ];

        return CMap::mergeArray($rules, parent::rules());
    }

    /**
     * @return array
     * @throws CException
     */
    public function attributeLabels()
    {
        $labels = [
            'api_key'         => $this->t('Api key'),
        ];
        return CMap::mergeArray($labels, parent::attributeLabels());
    }

    /**
     * @return array
     * @throws CException
     */
    public function attributePlaceholders()
    {
        $placeholders = [
            'api_key' => '',
        ];
        return CMap::mergeArray($placeholders, parent::attributePlaceholders());
    }

    /**
     * @inheritDoc
     */
    public function attributeHelpTexts()
    {
        $texts = [
            'api_key'         => $this->t('The api key for the service'),
        ];
        return CMap::mergeArray($texts, parent::attributeHelpTexts());
    }

    /**
     * @return bool
     */
    public function getIsEnabled(): bool
    {
        return $this->enabled === self::TEXT_YES;
    }


    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return (string)$this->api_key;
    }

    /**
     * @return array
     */
    public function getCustomerGroups(): array
    {
        return array_filter((array)$this->customer_groups);
    }

    /**
     * @return array
     */
    public function getCheckZones(): array
    {
        return array_filter((array)$this->check_zones);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'Zerobounce';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Check email address validity using Zerobounce service.';
    }

    /**
     * @inheritDoc
     */
    public function getOptionsPrefix(): string
    {
        return 'zerobounce';
    }

    /**
     * @inheritDoc
     */
    public function addFilter(): void
    {
        hooks()->addFilter('email_blacklist_is_email_blacklisted', [$this, '_emailBlacklistIsEmailBlacklisted']);
    }

    /**
     * @param mixed $isBlacklisted
     * @param string $email
     * @param ListSubscriber|null $subscriber
     * @param Customer|null $customer
     * @param array $params
     *
     * @return mixed
     */
    public function _emailBlacklistIsEmailBlacklisted($isBlacklisted, string $email, ?ListSubscriber $subscriber = null, ?Customer $customer = null, array $params = [])
    {
        // if already blacklisted we stop
        if ($isBlacklisted !== false) {
            return $isBlacklisted;
        }

        // without customer we stop
        if (empty($customer)) {
            return $isBlacklisted;
        }

        /** @var EmailVerificationExtZerobounceCustomer $customerSettings */
        $customerSettings = container()->get(EmailVerificationExtZerobounceCustomer::class);
        $customerSettings->setCustomer($customer);

        /** @var EmailVerificationExtZerobounceCommon $commonSettings */
        $commonSettings = container()->get(EmailVerificationExtZerobounceCommon::class);

        $checkZone = !empty($params['checkZone']) ? $params['checkZone'] : '';
        $enabled   = $customerSettings->getIsEnabled();
        $apiKey    = $customerSettings->getApiKey();

        // not enabled, no api key/url
        if (empty($enabled) || empty($apiKey)) {
            return $isBlacklisted;
        }

        /** @var CMap $emails */
        $emails = app_param('extensions.email-checkers.emails', new CMap());
        if ($emails->contains($email)) {
            return $emails->itemAt($email);
        }
        $emails->add($email, false);

        // check if the customer is allowed
        $allowedGroups = $commonSettings->getCustomerGroups();
        if (!empty($allowedGroups) && !in_array($customer->group_id, $allowedGroups)) {
            return $emails->itemAt($email);
        }

        // check if the zone is allowed
        $checkZones = $customerSettings->getCheckZones();
        if (!empty($checkZones) && !in_array($checkZone, $checkZones)) {
            return $emails->itemAt($email);
        }

        /** @var ZeroBounce\Response\ValidateResponse $response */
        $response = null;

        try {
            /** @var ZeroBounce\HttpClient\HttpClient $client */
            $client = new ZeroBounce\HttpClient\HttpClient($apiKey);

            /** @var ZeroBounce\Api $api */
            $api = new ZeroBounce\Api($client);

            /** @var ZeroBounce\Response\ValidateResponse $response */
            $response = $api->validate($email);
        } catch (Exception $e) {
            return $emails->itemAt($email);
        }

        if ($response->hasError() || !$response->hasStatus()) {
            $customerSettings->saveAttributes([
                'enabled' => EmailVerificationExtZerobounceCustomer::TEXT_NO,
            ]);

            $message = new CustomerMessage();
            $message->customer_id = (int)$customer->customer_id;
            $message->title       = 'Email verification';
            $message->message     = 'The "{name}" extension has been disabled, here is the service response: {response}';
            $message->message_translation_params = [
                '{name}'        => $this->getExtension()->name,
                '{response}'    => json_encode($response->getResponseData()),
            ];
            $message->save();

            return $emails->itemAt($email);
        }

        if ($response->isValid() || $response->isCatchAll() || $response->isUnknown()) {
            return $emails->itemAt($email);
        }

        $emails->add($email, $response->getStatus());

        return $emails->itemAt($email);
    }
}
