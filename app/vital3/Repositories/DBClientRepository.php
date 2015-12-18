<?php namespace vital3\Repositories;

use App\vital3\Client;

class DBClientRepository implements ClientRepositoryInterface {

    protected function rawFilter($input) {
        // Build a query based on filter $input
        $query = Client::orderBy('Client_Name', 'asc');
        if(isset($input['Client_Name']) && strlen($input['Client_Name']) > 2) {
            $query = $query->where('Client_Name', 'like', $input['Client_Name'] . '%');
        }
        if(isset($input['Address1']) && strlen($input['Address1']) > 3) {
            $query = $query->where('Address1', $input['Address1']);
        }
        if(isset($input['Address2']) && strlen($input['Address2']) > 3) {
            $query = $query->where('Address2', 'like', $input['Address2'] . '%');
        }
        if(isset($input['City']) && strlen($input['City']) > 3) {
            $query = $query->where('City', 'like', $input['City'] . '%');
        }
        if(isset($input['Province']) && strlen($input['Province']) > 3) {
            $query = $query->where('Province', 'like', $input['Province'] . '%');
        }
        if(isset($input['Post_Code']) && strlen($input['Post_Code']) > 3) {
            $query = $query->where('Post_Code', '=', $input['Post_Code']);
        }
        if(isset($input['Contact_Name']) && strlen($input['Contact_Name']) > 3) {
            $query = $query->where('Contact_Name', 'like', $input['Contact_Name'] . '%');
        }
        if(isset($input['Contact_email']) && strlen($input['Contact_email']) > 3) {
            $query = $query->where('Contact_email', 'like', $input['Contact_email'] . '%');
        }
        if(isset($input['Contact_phone']) && strlen($input['Contact_phone']) > 3) {
            $query = $query->where('Contact_phone', 'like', $input['Contact_phone'] . '%');
        }
        if(isset($input['Contact_fax']) && strlen($input['Contact_fax']) > 3) {
            $query = $query->where('Contact_fax', 'like', $input['Contact_fax'] . '%');
        }
        if(isset($input['Backup_Name']) && strlen($input['Backup_Name']) > 3) {
            $query = $query->where('Backup_Name', 'like', $input['Backup_Name'] . '%');
        }
        if(isset($input['Backup_email']) && strlen($input['Backup_email']) > 3) {
            $query = $query->where('Backup_email', 'like', $input['Backup_email'] . '%');
        }
        if(isset($input['Backup_phone']) && strlen($input['Backup_phone']) > 3) {
            $query = $query->where('Backup_phone', 'like', $input['Backup_phone'] . '%');
        }
        if(isset($input['Backup_fax']) && strlen($input['Backup_fax']) > 3) {
            $query = $query->where('Backup_fax', 'like', $input['Backup_fax'] . '%');
        }
        return $query;
    }

    /**
     * Implement filterOn()
     */
    public function filterOn($input, $limit=10) {
        if($limit == 0) {
            return $this->rawFilter($input)->get();
        } elseif($limit == 1) {
            return $this->rawFilter($input)->first();
        }
        return $this->rawFilter($input)->limit($limit)->get();
    }

    /**
     * Implement lists($limit=10)
     */
    public function lists($limit=10) {
        if($limit == 0) {
            return Client::orderBy('Client_Name', 'asc')->get();
        } elseif($limit == 1) {
            return Client::orderBy('Client_Name', 'asc')->first();
        }
        return Client::orderBy('Client_Name', 'asc')->limit($limit)->get();
    }

}
