<?php

namespace App\Repositories\Admin\Career;

use App\Models\Career\Career;
use DB;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\registrationMail;
use Illuminate\Support\Facades\Storage;
use Auth;

/**
 * Class CareerRepository.
 */
class CareerRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Career::class;
    protected $upload_path;
    protected $storage;

    /**
     * @var User Model
     */
    protected $model;

    /**
     *
     */
    public function __construct(Career $model)
    {
        $this -> model = $model;

        $this -> upload_path = 'img' . DIRECTORY_SEPARATOR . 'career_icon' . DIRECTORY_SEPARATOR;

        $this -> storage = Storage ::disk('public');
    }

    /**
     *
     * @return mixed
     */
    public function getForDataTable()
    {
        /**
         * Note: You must return deleted_at or the Career getActionButtonsAttribute won't
         * be able to differentiate what buttons to show for each row.
         */
        return $this -> query()
            -> select(['*']);
    }

    /**
     * @param array $input
     *
     * @return bool
     * @throws \App\Exceptions\GeneralException
     *
     */
    public function create(array $input)
    {

        DB ::transaction(function () use ($input) {
            // Uploading icon
            if (isset($input['icon_img']) && !empty($input['icon_img'])) {
                $input['icon'] = $input['icon_img'];
            } elseif (array_key_exists('icon', $input) && !empty($input['icon'])) {
                $input = $this -> uploadImage($input);
            }
            $input['slug']=strtolower(str_replace(' ', '-', $input['title']));
            if ($user = Career ::create($input)) {
                return true;
            }
            throw new GeneralException('There was a problem creating. Please try again.');
        });
    }

    /**
     * @param Model $user
     * @param $request
     *
     * @return bool
     * @throws GeneralException
     *
     */
    public function update($user, $request, $id)
    {

        DB ::transaction(function () use ($user, $request, $id) {

           // Uploading icon
            if (isset($request['icon_img']) && !empty($request['icon_img'])) {
                $request['icon'] = $request['icon_img'];
            } elseif (array_key_exists('icon', $request) && !empty($request['icon'])) {
                $request = $this -> uploadImage($request);
            }
            $request['slug']=strtolower(str_replace(' ', '-', $request['title']));

            if ($user -> update($request)) {
                return true;
            }

            throw new GeneralException('There was a problem in updating. Please try again.');
        });
    }

    public function sendUserRegistrationMail($user, $password)
    {
        // Send mail to User
        Mail ::to($user['email']) -> send(new registrationMail($user, $password));

        //Send SMS
        $message = "Hello " . $user['first_name'] . "";
    }


    /**
     * Delete User.
     *
     * @param Model $user
     *
     * @return bool
     * @throws GeneralException
     *
     */
    public function delete($user)
    {

        if ($user -> delete()) {
            if ($user -> icon != '' && file_exists(public_path('img/career_icon/') . $user -> icon)) {
                unlink(public_path('img/career_icon/') . $user -> icon);
            }
            return true;
        }

        throw new GeneralException('There was a problem in Deleting. Please try again.');
    }


    /**
     * Upload Image.
     *
     * @param array $input
     *
     * @return array $input
     */
    public function uploadImage($input)
    {


        if (isset($input['icon']) && !empty($input['icon'])) {
            $icon = $input['icon'];

            $fileName = time() . $icon -> getClientOriginalName();

            $this -> storage -> put($this -> upload_path . $fileName, file_get_contents($icon -> getRealPath()));

            $input['icon'] = $fileName;
            return $input;
        }
    }
}
