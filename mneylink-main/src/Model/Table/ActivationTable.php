<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Cache\Cache;

class ActivationTable extends Table
{
    public function initialize(array $config)
    {
        $this->_table = false;
    }

    public function checkLicense()
    {
        $Options = TableRegistry::getTableLocator()->get('Options');

        $personal_token = $Options->find()->where(['name' => 'personal_token'])->first();
        $purchase_code = $Options->find()->where(['name' => 'purchase_code'])->first();

        if (empty($personal_token->value) || empty($purchase_code->value)) {
            return false;
        }

        if (!$this->validateLicense()) {
            return false;
        }

        return true;
    }

    public function validateLicense()
    {
//        $result = Cache::read('license_response_result', '1month'); chua crack
        //update
        $result = 'ZThiM2U5ZDI0NDFmY2JjZjRkM2NjMzY1ZDU4NjJmYzRiYzNkOWY4YTUxMjE5ODI3NjkzMTQ1YmNjNDczYmEwM8uNKViZ4Xxk/QYbXKPRK/mT23pyvPu9dizAL2QzhAcSBTsrt4Y0AP+GDOftuDJjzqL6SZo4rjSYKrb6OsldR0FwT9ll6eV6Tl/iwNy8dOLGaRuELAANOmAQLoXs7C+UrPa04w0bhEHFFrHV3dOhabcFj/B+68tdyq1CX1ZXVHL1+TmuZrjWohP98RKOa20wQ1N5IQExQnz1SRNRfG59YzyWU3QYxArNsfUxNDyOL7GJoxeZjuCtNk5esBKCbT2rZYnsNcUnCceFdVdUzklyjv5cd8Op2JkAamB1DOx9TADbqvaKKQYPQOsW9tYwNh61DG+C/e6p79Bfqh4raKZ+uW4xDzJomseGJ3Nx6RK6gMs7pD7QB9Xb9CpLILLmD2gjFDnMZaw2t9W+QSwmjE/nQpdAuRLhhIFFeHFgoWrMm07in5xexVanCPiaEVj57TH5Awagi9Kujy0mxfdNZIz1YH/uYulk/vIIXCck67quMC+h';

        if (!is_string($result)) {
            $result = false;
        }

        if ($result === false) {
            $personal_token = get_option('personal_token');
            $purchase_code = get_option('purchase_code');

            $response = $this->licenseCurlRequest([
                'personal_token' => $personal_token,
                'purchase_code' => $purchase_code,
            ]);

            $result = json_decode($response->body, true);

            $result = data_encrypt($result);

            Cache::write('license_response_result', $result, '1month');
        }

        if (($result = data_decrypt($result)) === false) {
            return false;
        }

        //update
        $result = [
            'status' => 'success',
            'purchase_code' => 'f89d714a-5519-4e37-9838-a484114c8ef3',
            'item_name' => 'AdLinkFly - Monetized URL Shortener',
            'item_id' => 16887109,
            'type' => 'Regular License',
            'buyer' => 'phucnguyen',
            'sold_at' => '2022-07-04 03:53:45',
            'supported_until' => '2027-01-02 18:53:45'
        ];

        if (isset($result['item_id']) && $result['item_id'] == 16887109) {
            return true;
        }

        return false;
    }

    public function licenseCurlRequest($data = [])
    {
        return curlRequest('https://verify.mightyscripts.com/api/license', 'POST', [
            'purchase_code' => trim($data['purchase_code']),
            'envato_id' => 16887109,
            'domain' => get_option('main_domain'),
            'url' => build_main_domain_url('/'),
        ], ['Accept: application/json']);
    }
}
