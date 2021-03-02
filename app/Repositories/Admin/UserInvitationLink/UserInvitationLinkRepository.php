<?php
namespace App\Repositories\Admin\UserInvitationLink;
use App\Models\SendUserInviteLink\SendUserInviteLink;
use DB;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendInvitationLinkMail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class SendUserInviteLinkRepository.
 */
class UserInvitationLinkRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = SendUserInviteLink::class;

	/**
     * @var SendUserInviteLink Model
     */
	protected $model;
    /**
     * 
     */
    public function __construct(SendUserInviteLink $model)
    {
    	$this->model = $model;
    }
    /**
 
     *
     * @return mixed
     */
    public function getForDataTable()
    {
        /**
         * Note: You must return deleted_at or the SendUserInviteLink getActionButtonsAttribute won't
         * be able to differentiate what buttons to show for each row.
         */
        return $this->query()
        ->select(['*']);
    }
    /**
     * @param array $input
     *
     * @throws \App\Exceptions\GeneralException
     *
     * @return bool
     */
    public function create(array $input)
    {
    	DB::transaction(function () use ($input) {
            
            $input['code']=Str::random(16);
    		if (SendUserInviteLink::create($input)) {
                $this->sendInvitationLinkMail($input);
    			return true;
    		}

    		throw new GeneralException('There was a problem creating. Please try again.');
    	});
    }

     public function sendInvitationLinkMail($user)
    {
        // Send mail to User
        Mail::to($user['email'])->send(new SendInvitationLinkMail($user));
    }

    /**
     * @param Model $SendUserInviteLink
     * @param $request
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function update($SendUserInviteLink, $request,$id)
    {

    	DB::transaction(function () use ($SendUserInviteLink, $request,$id) {

            // Uploading image
            if (array_key_exists('image', $request)) {
                $request=$this->uploadImage($request);
            } 

            if ($SendUserInviteLink->update($request)) {
             return true;
         }

         throw new GeneralException('There was a problem in updating. Please try again.');
     });
    }


    /**
     * Delete SendUserInviteLink.
     *
     * @param Model $SendUserInviteLink
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete($SendUserInviteLink)
    {

    	if ($SendUserInviteLink->delete()) {
    		return true;
    	}

    	throw new GeneralException('There was a problem in Deleting. Please try again.');
    }

}
