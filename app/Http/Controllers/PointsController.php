<?php

namespace App\Http\Controllers;

use App\ApplicationException;
use App\PointsRepository;
use App\Rules\City;
use App\Rules\Latitude;
use App\Rules\Longitude;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use function response;
use Illuminate\Http\JsonResponse;

class PointsController extends Controller
{

    protected $pointsRepository;

    public function __construct(PointsRepository $pointsRepository)
    {
        $this->pointsRepository = $pointsRepository;
    }

    /**
     * POST /points
     *
     * Creates new point
     *
     * Parameters:
     *
     * lat - latitude
     * lon - longitde
     * city - city name
     * name - point name
     * desc - short point description
     *
     * @param Request $request
     * @throws ApplicationException
     * @return JsonResponse;
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => ['required', new Latitude()],
            'lon' => ['required', new Longitude()],
            'city' => ['required', new City()],
            'name' => 'required|string|max:255',
            'desc' => 'required|string|max:4000'
        ]);

        if ($validator->fails()) {
            $result = ['success' => false, 'errors' => ['validation' => $validator->errors()]];
            $code = 400;
        } else {
            $created = $this->pointsRepository->create($request->all());
            $result['success'] = $created;
            $code = $created ? 201 : 500;
        }

        return response()->json($result, $code);
    }

    /**
     * PUT /points/{id}
     *
     * Updates existing point
     *
     * Parameters:
     *
     * id - point id
     * lat - latitude
     * lon - longitude
     * city - city name
     * name - point name
     * desc - short point description
     *
     * @param int     $id
     * @param Request $request
     * @throws ApplicationException
     * @return JsonResponse;
     */
    public function update(int $id, Request $request)
    {
        $validator =  $validator = Validator::make($request->all(), [
            'lat' => [new Latitude()],
            'lon' => [new Longitude()],
            'city' => [new City()],
            'name' => 'string|max:255',
            'desc' => 'string|max:4000'
        ]);

        if ($validator->fails()) {
            $result = ['success' => false, 'errors' => ['validation' => $validator->errors()]];
            $code = 400;
        } else {
            $updated = $this->pointsRepository->update($id, $request->all());

            $result = ['success' => $updated];

            $code = 200;
        }

        return response()->json($result, $code);
    }

    /**
     * GET /points/inrad/{radius}?ip=1.1.1.1
     *
     * Returns nearest points by ip and radius
     *
     * Parameters:
     *
     * rad - radius in meters (default - 1000)
     * ip  - client ip (optional)
     *
     * @param Request $request
     * @throws ApplicationException
     * @return JsonResponse;
     */
    public function showAllPointsInRadius(Request $request)
    {
        $data = $request->all();

        $ip = $data['ip'] ?? $request->ip();

        $radius = $data['rad'] ?? 1000;

        $validator = Validator::make(compact('radius', 'ip'), [
            'radius' => 'integer|max:10000',
            'ip' => 'ip',
        ]);

        if ($validator->fails()) {
            $result = ['success' => false, 'errors' => ['validation' => $validator->errors()]];
            $code = 400;
        } else {
            $collection = $this->pointsRepository->findByIpInRadius($ip, $radius);
            $prepared = $this->preparedData($collection);
            $result = ['success' => true, 'data' => $prepared];
            $code = 200;
        }

        return response()->json($result, $code);
    }

    /**
     * GET /points/in/{city}?limit=50&offset=10
     *
     * Returns points by city name
     *
     * Parameters:
     *
     * city - city name
     * limit - number of places for return
     * offset - offset
     *
     * @param string $city
     * @param Request $request
     * @throws ApplicationException
     * @return JsonResponse;
     */
    public function showAllPointsInCity(string $city, Request $request)
    {
        $data = $request->all();

        $limit = $data['limit'] ?? 50;

        $offset = $data['offset'] ?? 0;

        $validator = Validator::make(compact('city', 'limit', 'offset'), [
            'limit' => 'integer|max:100',
            'offset' => 'integer',
            'city' => new City(),
        ]);

        if ($validator->fails()) {
            $result = ['success' => false, 'errors' => ['validation' => $validator->errors()]];
            $code = 400;
        } else {
            $collection = $this->pointsRepository->findAllInCity($city, $limit, $offset);

            $prepared = $this->preparedData($collection, false);
            $result = ['success' => true, 'data' => $prepared];
            $code = 200;
        }

        return response()->json($result, $code);
    }

    /**
     * Prepares response data
     *
     * @param Collection $collection
     * @param bool $showCity
     * @return array
     */
    private function preparedData(Collection $collection, $showCity = true): array
    {
        return $collection->map(function ($item) use ($showCity) {
            $arr['name'] = $item->name;
            $arr['desc'] = $item->desc;
            $arr['lat'] = $item->location->getLat();
            $arr['lon'] = $item->location->getLng();
            if ($showCity) {
                $arr['in'] = $item->city->name;
            }
            return $arr;
        })
            ->all();
    }

}
