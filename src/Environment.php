<?php

namespace Smolblog\Core;

use Smolblog\Core\Definitions\Endpoint;
use Smolblog\Core\Exceptions\EnvironmentException;

/**
 * A singleton class for handling interactions between the Smolblog libraries
 * and the broader platform/framework/environment it is running on.
 *
 * This class should be extended with a class built to handle implementation-
 * specific functionality. An instance of that class should then be passed
 * to the static `bootstrap` function to make it available to the core
 * libraries.
 *
 * When adding new instance methods, provide a default if it makes logical
 * sense. If a default value cannot be inferred or would otherwise be
 * illogical, throw an exception. Avoid abstract functions as not all
 * environments will make use of all features.
 */
class Environment {
	// Static methods.

	/**
	 * Store the current Environment.
	 *
	 * @var Environment
	 */
	private static ?Environment $singleton = null;

	/**
	 * Callbacks to call after the environment is bootstrapped.
	 *
	 * @var array
	 */
	private static $callAfterBootstrap = [];

	/**
	 * Queue $callback for calling after the Environment is bootstrapped.
	 *
	 * If the environment is already bootstrapped, $callback is called immediately.
	 *
	 * @param callable $callback Callable to run after bootstrapping.
	 * @return void
	 */
	public static function addBootstrapCallback(callable $callback): void {
		if (!self::$singleton) {
			self::$callAfterBootstrap[] = $callback;
			return;
		}

		// If we are already bootstrapped, then just run the function.
		$callback();
	}

	/**
	 * Load the given Environment as the current Environment.
	 *
	 * @param Environment $withEnvironment Environment for this implementation.
	 * @throws EnvironmentException When this function is called multiple times.
	 * @return void
	 */
	public static function bootstrap(Environment $withEnvironment): void {
		if (self::$singleton) {
			throw new EnvironmentException(
				environment: self::$singleton,
				message: 'Smolblog\\Core\\Environment::bootstrap should only be called ONCE.'
			);
		}

		self::$singleton = $withEnvironment;

		foreach (self::$callAfterBootstrap as $callback) {
			$callback();
		}
	}

	/**
	 * Get the environment instance.
	 *
	 * @throws EnvironmentException When `bootstrap` has not been called.
	 * @return Environment Environment for this implementation.
	 */
	public static function get(): Environment {
		if (!self::$singleton) {
			throw new EnvironmentException(
				environment: null,
				message: 'Smolblog environment has not been bootstrapped yet.'
			);
		}

		return self::$singleton;
	}

	// Instance methods.

	/**
	 * Get a ModelHelper appropriate for the given class.
	 *
	 * @throws EnvironmentException When this function is called without being implemented.
	 * @param string $modelClass Fully-qualified class name of the model to help.
	 * @return ModelHelper An instantiated ModelHelper for this class.
	 */
	public function getHelperForModel(string $modelClass): ModelHelper {
		throw new EnvironmentException(
			environment: self::$singleton,
			message: 'getHelperForModel was called without being implemented.'
		);

		return new stdClass();
	}

	/**
	 * Get environment variable. Wrapper around `getenv`.
	 *
	 * @param string $name Name of the environment variable.
	 * @return mixed
	 */
	public function envVar(string $name): mixed {
		return getenv($name);
	}

	/**
	 * Store a temporary value that needs to persist between requests.
	 *
	 * @throws EnvironmentException When this function is called without being implemented.
	 * @param string  $name                   Name to recall this value by.
	 * @param mixed   $value                  Value to store.
	 * @param integer $secondsUntilExpiration Keep the value for up to this many seconds.
	 * @return void
	 */
	public function setTransient(string $name, mixed $value, int $secondsUntilExpiration): void {
		throw new EnvironmentException(
			environment: self::$singleton,
			message: 'setTransient was called without being implemented.'
		);
	}

	/**
	 * Get a transient value if it exists.
	 *
	 * @throws EnvironmentException When this function is called without being implemented.
	 * @param string $name Name of the transient.
	 * @return mixed Stored value; null if not found or expired.
	 */
	public function getTransientValue(string $name): mixed {
		throw new EnvironmentException(
			environment: self::$singleton,
			message: 'getTransientValue was called without being implemented.'
		);

		return null;
	}

	/**
	 * Get the base URL for the REST API endpoints. Includes a trailing slash.
	 *
	 * @throws EnvironmentException When this function is called without being implemented.
	 * @return string
	 */
	public function getBaseRestUrl(): string {
		throw new EnvironmentException(
			environment: self::$singleton,
			message: 'getBaseRestUrl was called without being implemented.'
		);

		return $route;
	}
}
