<?php

class WP_NoCaptcha_Plugin {
	
	protected $_cookieName = 'nocpatchatok';
	protected $_salt = '+MuZ0(<-Unvqg|[Fb^{?N0wW>l92ttzZqAB|9+*<ZYk}6.*ixg{AdHjIo>Fj(!T2';
	protected $_value;

	public function __construct()
	{
		$this->readCookie();
	}

	public function generateSignedValue($value)
	{
		return sha1($this->_salt.$value).'~'.$value;
	}

	public function extractSignedValue($rawValue)
	{
		$chunks = explode('~', $rawValue);

		if ($rawValue !== '~' && count($chunks) === 2)
		{
			if ($chunks[0] === $this->generateSignedValue($chunks[1]))
			{
				return $chunks[1];
			}
		}

		return NULL;
	}

	public function readCookie()
	{
		if (isset($_COOKIE[$this->_cookieName]) && ! empty($_COOKIE[$this->_cookieName]))
		{
			$this->_value = $this->extractSignedValue($_COOKIE[$this->_cookieName]);
		}

		return $this->_value;
	}

	public function writeCookie()
	{
		if (!empty($this->_value))
		{
			$token = $this->generateSignedValue($this->_value);

			// Expire previous
			set_cookie($this->_cookieName, '', time() - (3600*24), '/');
			set_cookie($this->_cookieName, $token, time() + (3600*24), '/');
		}

		return $this;
	}

	public function getToken()
	{
		if ($this->_value === NULL)
		{
			$this->readCookie();
		}

		return $this->_value;
	}

	public function setToken($token)
	{
		$this->_value = $token;

		return $this;
	}
}