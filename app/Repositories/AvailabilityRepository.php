<?php

namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\Models\Availability;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

/**
 * Class AvailabilityRepository.
 */
class AvailabilityRepository implements CrudInterface
{
    /**
     * Authenticated User Instance.
     *
     * @var User
     */
    public User|null $user;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->user = Auth::guard()->user();
    }

    /**
     * Get All Availability.
     *
     * @return collections Array of Availability Collection
     */
    public function getAll(): Paginator
    {
        if (! $this->user) {
            return User::query()->paginate(10);
        }

        return $this->user->availability()
            ->orderBy('id', 'desc')
            ->with('user')
            ->paginate(10);
    }

    /**
     * Get Paginated Availability Data.
     *
     * @param  int  $pageNo
     * @return collections Array of Availability Collection
     */
    public function getPaginatedData($perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 10;

        return Availability::orderBy('id', 'desc')
            ->with('user')
            ->paginate($perPage);
    }

    /**
     * Create New Availability.
     *
     * @return object Product Object
     */
    public function create(array $data): Availability
    {
        $user = Auth::user();
        $data['user_id'] = $user->id;

        // Convert the date format from "dd-mm-yyyy H:i" to "yyyy-mm-dd H:i:s"
        if (isset($data['start_time'])) {
            $data['start_time'] = date('Y-m-d H:i:s', strtotime($data['start_time']));
        }

        if (isset($data['finish_time'])) {
            $data['finish_time'] = date('Y-m-d H:i:s', strtotime($data['finish_time']));
        }

        return Availability::create($data);
    }

    /**
     * Delete Availability.
     *
     * @return bool true if deleted otherwise false
     */
    public function delete(int $id): bool
    {
        $Availability = Availability::find($id);
        if (empty($Availability)) {
            return false;
        }
        $Availability->delete($Availability);

        return true;
    }

    /**
     * Get Availability Detail By ID.
     *
     * @return void
     */
    public function getByID(int $id): Availability|null
    {
        return Availability::with('user')->find($id);
    }

    public function getProductByCode($AvailabilityCode)
    {
        $Availability = Availability::where('Availability_code', $AvailabilityCode)->first();

        if ($Availability) {
            return response()->json($Availability);
        } else {
            return response()->json(['error' => 'Availability not found'], 404);
        }
    }

    /**
     * Update Availability By ID.
     *
     * @return object Updated Availability Object
     */
    public function update(int $id, array $data)
    {
        $availability = Availability::find($id);

        // If the availability is not found, return null
        if (is_null($availability)) {
            return null;
        }

        // Convert the date format from "dd-mm-yyyy H:i" to "yyyy-mm-dd H:i:s"
        if (isset($data['start_time'])) {
            $data['start_time'] = date('Y-m-d H:i:s', strtotime($data['start_time']));
        }

        if (isset($data['finish_time'])) {
            $data['finish_time'] = date('Y-m-d H:i:s', strtotime($data['finish_time']));
        }

        // Update the availability data
        $availability->update($data);

        // Return the updated availability
        return $availability;
    }
}
