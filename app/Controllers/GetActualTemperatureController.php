<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Model\Services\WeatherRepository;
use Contributte\ApiRouter\ApiRoute;
use Nette\Application\Request;
use Nette\Application\Response;
use App\Utils\Responses\ExtendedJsonResponse as JsonResponse;
use Nette\Http\IResponse;
use Tracy\Debugger;


/**
 * API for logging users in
 *
 * @ApiRoute(
 * 	"/api/get_actual_temperature",
 *
 * 	methods={
 * 		"GET"="run"
 * 	},
 *  presenter="GetActualTemperature"
 * )
 */
final class GetActualTemperatureController extends AbstractController
{

    /** @var WeatherRepository @inject */
    public WeatherRepository $weatherRepository;

	public function run(Request $request): Response
	{
        try {

            $temperature = $this->weatherRepository->findOneWeatherBy([], ['date' => 'DESC'])->getTemperature();

            return new JsonResponse($this->apiResponseFormatter->formatPayload(['temperature' => $temperature]), IResponse::S200_OK);

        } catch (\Exception $e) {
            return new JsonResponse($this->apiResponseFormatter->formatError($e->getCode(), $e->getMessage()), IResponse::S500_InternalServerError);
        }

	}
}
