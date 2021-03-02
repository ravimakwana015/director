<?php

namespace App\Repositories\Admin\User;

use App\Models\UsersPersonalityTraits\UsersPersonalityTraits;
use App\User;
use DB;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\registrationMail;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\Message;
use App\Friend;
use Image;
use App\Models\UserFeed\UserFeed;
use Spatie\Activitylog\Models\Activity;


/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = User::class;
    protected $upload_path;
    protected $storage;

    /**
     * @var User Model
     */
    protected $model;

    /**
     *
     */
    public function __construct(User $model)
    {
        $this -> model = $model;

        $this -> upload_path = 'img' . DIRECTORY_SEPARATOR . 'profile_picture' . DIRECTORY_SEPARATOR;

        $this -> storage = Storage ::disk('public');
    }

    /**
     *
     * @return mixed
     */
    public function getForDataTable()
    {
        /**
         * Note: You must return deleted_at or the User getActionButtonsAttribute won't
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
            // Uploading profile_picture
            if (array_key_exists('profile_picture', $input) && !empty($input['profile_picture'])) {
                $input = $this -> uploadImage($input);
            }

            $password = $input['password'];
            $input['password'] = bcrypt($input['password']);
            if ($user = User ::create($input)) {
                UsersPersonalityTraits ::create([
                    'user_id' => $user -> id,
                    'loneliness' => 5,
                    'entertainment' => 5,
                    'curiosity' => 5,
                    'relationship' => 5,
                    'hookup' => 5,
                ]);
                $this -> sendUserRegistrationMail($input, $password);
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

            // Uploading profile_picture
            if (array_key_exists('profile_picture', $request)) {
                if ($user -> profile_picture != '' && file_exists(public_path('img/profile_picture/') . $user -> profile_picture)) {
                    unlink(public_path('img/profile_picture/') . $user -> profile_picture);
                }
                if ($user -> profile_picture != '' && file_exists(public_path('img/profile_picture/225') . $user -> profile_picture)) {
                    unlink(public_path('img/profile_picture/225') . $user -> profile_picture);
                }
                $request = $this -> uploadImage($request);
            }

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

        if ($user -> forceDelete()) {

            if ($user -> profile_picture != '' && file_exists(public_path('img/profile_picture/') . $user -> profile_picture)) {
                unlink(public_path('img/profile_picture/') . $user -> profile_picture);
            }
            if ($user -> profile_picture != '' && file_exists(public_path('img/profile_picture/225/') . $user -> profile_picture)) {
                unlink(public_path('img/profile_picture/225/') . $user -> profile_picture);
            }
            if ($user -> cv != '' && file_exists(public_path('img/cv/') . $user -> cv)) {
                unlink(public_path('img/cv/') . $user -> cv);
            }
            if (isset($user -> gallery) && count($user -> gallery)) {
                foreach ($user -> gallery as $image) {
                    if ($image -> image != '' && file_exists(public_path('img/user_gallery/') . $image -> image)) {
                        unlink(public_path('img/user_gallery/') . $image -> image);
                    }
                }
            }
            if (isset($user -> videoGallery) && count($user -> videoGallery)) {
                foreach ($user -> videoGallery as $video) {
                    if ($video -> image != '' && file_exists(public_path('img/video_gallery') . $video -> image)) {
                        unlink(public_path('img/video_gallery/') . $video -> image);
                    }
                }
            }
            if (isset($user -> userFeed) && count($user -> userFeed)) {
                foreach ($user -> userFeed as $feed) {
                    $img = json_decode($feed -> properties);
                    if (isset($img -> image) && $img -> image != '' && file_exists(public_path('img/feed_images/') . $img -> image)) {
                        unlink(public_path('img/feed_images/') . $img -> image);
                    }
                }
            }
            Activity ::where('causer_id', $user -> id) -> delete();
            UserFeed ::where('user_id', $user -> id) -> delete();
            Message ::where('receiver_id', $user -> id) -> delete();
            Message ::where('user_id', $user -> id) -> delete();
            Friend ::where('friend_id', $user -> id) -> delete();
            Friend ::where('user_id', $user -> id) -> delete();
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


        if (isset($input['profile_picture']) && !empty($input['profile_picture'])) {
            $profile_picture = $input['profile_picture'];

            $fileName = time() . $profile_picture -> getClientOriginalName();

            $this -> storage -> put($this -> upload_path . $fileName, file_get_contents($profile_picture -> getRealPath()));

            $img = Image ::make($input['profile_picture']) -> resize(225, 225);
            if (!file_exists(public_path() . "/img/profile_picture/225")) {
                mkdir(public_path() . "/img/profile_picture/225", 0777, true);
            }
            $img -> save(public_path() . "/img/profile_picture" . '/225/' . $fileName . '', 100);

            $input['profile_picture'] = $fileName;
            return $input;
        }
    }
}
