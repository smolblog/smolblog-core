<?php

namespace Smolblog\Core\Endpoints;

use Smolblog\Core\{Endpoint, EndpointRequest, EndpointResponse, Environment};
use Smolblog\Core\Definitions\{HttpVerb, SecurityLevel};
use Smolblog\Core\EndpointParameters\{ConnectorSlug, StringParameter};
use Smolblog\Core\Registrars\ConnectorRegistrar;

/**
 * Endpoint to handle an OAuth2 callback from a Connector's provider
 */
class ConnectCallback extends Endpoint {
	/**
	 * Set properties for this endpoint.
	 *
	 * @return void
	 */
	protected function initValues(): void {
		$this->route = 'connect/callback/[slug]';
		$this->verbs = [HttpVerb::GET];
		$this->security = SecurityLevel::Anonymous;
		$this->params = [
			new ConnectorSlug(name: 'slug', isRequired: true),
			new StringParameter(name: 'state', isRequired: true),
			new StringParameter(name: 'code', isRequired: true),
		];
	}

	/**
	 * Perform the action associated with this endpoint and return the response.
	 *
	 * @param EndpointRequest $request Full information of the HTTP request.
	 * @return EndpointResponse Response to give
	 */
	public function run(EndpointRequest $request): EndpointResponse {
		$connector = ConnectorRegistrar::retrieve($request->params['slug']);
		$info = Environment::get()->getTransientValue(name: $request->params['state']);

		if (!isset($info)) {
			return new EndpointResponse(
				statusCode: 400,
				body: ['error' => 'An matching request was not found; please try again.'],
			);
		}

		$credential = $connector->createCredential(code: $request->params['code'], info: $info);

		return new EndpointResponse(statusCode: 200, body: ['credentialId' => $credential->id]);
	}
}
