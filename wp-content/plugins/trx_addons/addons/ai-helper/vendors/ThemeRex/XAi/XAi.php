<?php

namespace ThemeRex\XAi;

use Exception;
use ThemeRex\Ai\Api;

class XAi extends Api {

	private $chatModel = "grok-3-latest";

	private $customUrl = "";

	public function __construct( $api_key )	{
		parent::__construct( $api_key );
	}

	/**
	 * Set a custom URL instead the default URL for the API requests
	 * 
	 * @param  string  $customUrl  The custom URL
	 */
	public function setBaseUrl( string $customUrl ) {
		if ( $customUrl != '' ) {
			$this->customUrl = $customUrl;
		}
	}

	/**
	 * Prepare a base URL for the API: if the custom URL is set, replace the default URL with it
	 * 
	 * @param  string  $url
	 */
	protected function baseUrl( string &$url ) {
		if ( $this->customUrl != "" ) {
			$url = str_replace( Url::ORIGIN, $this->customUrl, $url );
		}
		// Filter to allow the child-theme replace an URL with own server URL with local models
		$url = apply_filters( 'trx_addons_filter_ai_helper_x_ai_base_url', $url, Url::ORIGIN, $this->customUrl );
	}

	/**
	 * Check and prepare the arguments for the request
	 * 
	 * @param array $args  The arguments for the request
	 * 
	 * @return array  The arguments for the request
	 */
	private function checkArgs( $args ) {
		unset( $args['size'] );
		return apply_filters( 'trx_addons_filter_ai_helper_check_args', $args, 'x-ai' );
	}


	//============================ MODELS ============================

	/**
	 * Return a list of available models from the API
	 * 
	 * @param  string  $type  The type of models to return: 'all', 'text', 'image'
	 * 
	 * @return bool|array The response from the API
	 */
	public function listModels( $type = 'all' ) {
		$url = Url::models( $type );
		$this->baseUrl( $url );

		return $this->sendRequest( $url, 'GET' );
	}

	/**
	 * Get a model info from the API server
	 * 
	 * @param $model  The model name
	 * @param  string  $type  The type of models to return: 'all', 'text', 'image'
	 * 
	 * @return bool|array  The response from the API
	 */
	public function retrieveModel( $model, $type = 'all' ) {
		$url   = Url::models( $type ) . '/' . $model;
		$this->baseUrl( $url );

		return $this->sendRequest( $url, 'GET' );
	}


	//============================ IMAGES ============================

	/**
	 * @param $opts
	 * 
	 * @return bool|array The response from the API
	 */
	public function image( $opts ) {
		$url = Url::imageUrl();
		$this->baseUrl( $url );

		return $this->sendRequest( $url, 'POST', $this->checkArgs( $opts ) );
	}


	//============================ CHAT & COMPLETIONS ============================

	/**
	 * @param        $opts
	 * @param  null  $stream  The stream function
	 * 
	 * @return bool|array The response from the API
	 * 
	 * @throws Exception
	 */
	public function chat( $opts, $stream = null ) {
		if ( $stream != null && array_key_exists( 'stream', $opts ) ) {
			if ( ! $opts['stream'] ) {
				throw new Exception( __( 'Please provide a stream function.', 'trx_addons' ) );
			}

			$this->setStreamMethod( $stream );
		}

		$opts['model'] = $opts['model'] ?? $this->chatModel;
		$url           = Url::chatUrl();
		$this->baseUrl( $url );

		return $this->sendRequest( $url, 'POST', $this->checkArgs( $opts ) );
	}
}
