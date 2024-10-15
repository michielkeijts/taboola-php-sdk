<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 *
 * @Licence MIT
 */

namespace TaboolaApi\Client\Authentication;

use TaboolaApi\Vault\NullVault;
use TaboolaApi\Vault\VaultInterface;

abstract class BaseAuthentication {
    /**
     * To use a vault to store an acces token when obtained
     * @var TaboolaApi\Vault\VaultInterface
     */
    private $vault;

    protected string $client_id;
    protected string $client_secret;
    protected string $username;
    protected string $password;

    /**
     * Creates the Authentication Class.
     *
     * $options:
     *  'Vault' =>
     *    'className' => name of Vault class
     *
     * @param string $client_id
     * @param string $client_secret
     * @param string $username
     * @param string $password
     * @param array $options
     */
    public function __construct(string $client_id, string $client_secret, string $username = "", string $password = "", array $options = [])
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->username = $username;
        $this->password = $password;

        if (!isset($options['Vault'])) {
            $options['Vault'] = [];
        }

        $options['Vault'] = [
            'className' => NullVault::class
        ] + $options['Vault'];

        $this->createVault($options['Vault']);
    }



    /**
     * Authenticate. Should return the AccesToken
     * @return string
     */
    public function Authenticate() : string
    {
        return "";
    }

    /**
     * Gets the access token. Checks if it is still valid.
     * Otherwise: get new token.
     * @return string
     */
    public function getAccesToken() : string
    {
        $token = $this->getVault()->read($this->client_id);

        if (!empty($token))
            return $token;

        return $this->saveAccesToken($this->Authenticate());
    }

    /**
     * Saves the token to the Vault
     * @param string $token
     * @return string $token
     */
    public function saveAccesToken(string $token) : string
    {
        $this->getVault()->write($this->client_id, $token);
        return $token;
    }

    /**
     * Saves the token to the Vault
     * @param string $token
     * @return string $token
     */
    public function revokeAccesToken(string $token) : bool
    {
        return $this->getVault()->delete($this->client_id);
    }

    /**
     * Creates a vault based on the options, which needs to have a
     * ['className'  => 'TaboolaApi\Vault\NullVault']
     *
     * @return \TaboolaApi\Vault\VaultInterface
     */
    private function createVault(array $options = [])
    {
        $this->vault = new $options['className']();

        $this->vault->applyOptions($options);

        return $this->vault;
    }


    /**
     * @return TaboolaApi\Vault\VaultInterface
     */
    public function getVault() : VaultInterface
    {
        return $this->vault;
    }
}
