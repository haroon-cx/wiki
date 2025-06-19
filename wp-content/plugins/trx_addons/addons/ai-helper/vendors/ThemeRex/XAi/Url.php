<?php

namespace ThemeRex\XAi;

class Url {

	public const ORIGIN = 'https://api.x.ai';
	public const SITE = 'https://console.x.ai';
	public const API_VERSION = 'v1';

	private static $endpoints = array(
		'chat' => 'chat/completions',
		'image' => 'images/generations',
		'all-models' => 'models',
		'text-models' => 'language-models',
		'image-models' => 'image-generation-models',
	);


	/**
	 * @return string
	 */
	public static function baseURL( $endpoint = '', $type = 'api' ): string {
		return ( $type == 'api' ? self::ORIGIN . "/" . self::API_VERSION : self::SITE ) . ( ! empty( $endpoint ) ? "/{$endpoint}" : '' );
	}

	/**
	 * @return string
	 */
	public static function models( $type = 'all' ): string {
		return self::baseURL( self::$endpoints[ $type . '-models' ] );
	}

	/**
	 * @return string
	 */
	public static function chatUrl(): string {
		return self::baseURL( self::$endpoints[ 'chat' ] );
	}

	/**
	 * @return string
	 */
	public static function imageUrl(): string {
		return self::baseURL( self::$endpoints[ 'image' ] );
	}
}
