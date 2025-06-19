<?php

namespace ThemeRex\StabilityAi;

use ThemeRex\Ai\Api;

class Images extends Api {

	private static $api_server  = "https://api.stability.ai";
	private static $site_server = "https://platform.stability.ai";

	private $api_version = 'v1';

	public function __construct( $api_key )	{
		parent::__construct( $api_key );

		$this->api_version = trx_addons_get_option( 'ai_helper_default_api_stability_ai', 'v1' );

		$this->setHeaders( array( 'Accept: application/json' ) );
	}

	/**
	 * Return a base URL to the vendor site
	 * 
	 * @param string $endpoint  The endpoint to use
	 * @param string $type  The type of the URL: api, site. Default: api
	 * 
	 * @return string  The URL to the vendor site
	 */
	public static function baseUrl( $endpoint = '', $type = 'api' ) {
		return ( $type == 'api' ? self::$api_server : self::$site_server ) . ( ! empty( $endpoint ) ? "/{$endpoint}" : '' );
	}

	/**
	 * Return an URL to the API
	 * 
	 * @param string $engine    The engine to use. For image generation, use the model ID.
	 * @param string $endpoint  The endpoint to use: text-to-image, image-to-image
	 * 
	 * @return string  The URL to the API
	 */
	public function apiUrl( $engine, $endpoint ) {
		return self::baseUrl( "{$engine}/{$endpoint}" );
	}

	private function checkArgs( $args ) {
		return apply_filters( 'trx_addons_filter_ai_helper_check_args', $args, 'stability-ai' );
	}

	/**
	 * Return a list of available models
	 * 
	 * @return bool|string  The response from the API
	 */
	public function listModels( $opts = array() ) {
		if ( $this->api_version == 'v1' ) {
			$url = $this->apiUrl( 'v1/engines', 'list' );
			return $this->sendRequest( $url, 'GET', array( 'key' => ! empty( $opts['key'] ) ? $opts['key'] : $this->api_key ) );
		}
		return false;
	}

	/**
	 * Generate an image from a text prompt
	 * 
	 * @param array $opts  The options for the request
	 * 
	 * @return bool|string  The response from the API
	 */
	public function textToImage( $opts ) {
		// Get the API URL
		if ( $this->api_version == 'v2' ) {
			$opts['content_type'] = 'multipart/form-data';
			$url = $this->apiUrl( "v2beta/stable-image/generate", $opts['model_id'] );
		} else {
			$url = $this->apiUrl( "v1/generation/{$opts['model_id']}", 'text-to-image' );
		}
		// Send the request
		return $this->sendRequest( $url, 'POST', $this->checkArgs( $opts ) );
	}

	/**
	 * Generate an image from another image
	 * 
	 * @param array $opts  The options for the request
	 * 
	 * @return bool|string  The response from the API
	 */
	public function imageToImage( $opts ) {
		// Get the image from the URL
		if ( ! empty( $opts['init_image'] ) ) {
			$opts['content_type'] = 'multipart/form-data';
			if ( $this->api_version == 'v2' ) {
				$opts['image'] = curl_file_create( $opts['init_image'] );
				unset( $opts['cfg_scale'] );
				if ( empty( $opts['strength'] ) ) {
					$opts['strength'] = 0.7;
				}
				if ( empty( $opts['prompt'] ) ) {
					$opts['prompt'] = __( 'Create a variation of this image.', 'trx_addons' );
				}
				if ( $opts['model_id'] == 'sd3' ) {
					$opts['mode'] = 'image-to-image';
				}
			} else {
				$opts['init_image'] = curl_file_create( $opts['init_image'] );
			}
		}
		// Get the API URL
		if ( $this->api_version == 'v2' ) {
			$url = $this->apiUrl( "v2beta/stable-image/generate", $opts['model_id'] );
		} else {
			$url = $this->apiUrl( "v1/generation/{$opts['model_id']}", 'image-to-image' );
		}
		// Remove unnessesary parameters
		unset( $opts['model_id'] );
		// Send the request
		return $this->sendRequest( $url, 'POST', $this->checkArgs( $opts ) );
	}

	/**
	 * Upscale an image
	 * 
	 * @param array $opts  The options for the request
	 * 
	 * @return bool|string  The response from the API
	 */
	public function imageUpscale( $opts ) {
		// Get the image from the URL
		if ( ! empty( $opts['init_image'] ) ) {
			$opts['content_type'] = 'multipart/form-data';
			$opts['image'] = curl_file_create( $opts['init_image'] );
			unset( $opts['init_image'] );
			if ( $this->api_version == 'v2' ) {
				if ( empty( $opts['prompt'] ) && $opts['model_id'] != 'fast' ) {
					$opts['prompt'] = __( 'Upscale this image to the 4K resolution', 'trx_addons' );
				}
			}
		}
		// Get the API URL
		if ( $this->api_version == 'v2' ) {
			$url = $this->apiUrl( "v2beta/stable-image/upscale", $opts['model_id'] );
		} else {
			$url = $this->apiUrl( "v1/generation/{$opts['model_id']}", 'image-to-image/upscale' );
		}
		// Remove unnessesary parameters
		unset( $opts['model_id'] );
		// Send the request
		return $this->sendRequest( $url, 'POST', $this->checkArgs( $opts ) );
	}

}
