<?php

namespace App\Repositories;

use App\Repositories\Models\Admin;

class AdminRepository implements AdminRepositoryInterface
{
    /** @var Admin */
    protected $admin;

    /**
     * @param Admin $admin
     */
    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    public function find($id)
    {
        try {
            $admin = $this->admin->find($id);
            if (!$admin) {
                $admin = new Admin();
            }
            return $admin;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function store($id, $requestArray)
    {
        try {
            $this->admin->updateOrCreate(
                ['id' => $id],
                ['admin' => $requestArray['admin']]
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
