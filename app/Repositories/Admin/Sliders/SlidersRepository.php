<?php
namespace App\Repositories\Admin\Sliders;
use App\Models\Sliders\Sliders;
use DB;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\registrationMail;
use Illuminate\Support\Facades\Storage;

/**
 * Class SlidersRepository.
 */
class SlidersRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Sliders::class;
    protected $upload_path;
    protected $storage;

	/**
     * @var Sliders Model
     */
	protected $model;
    /**
     *
     */
    public function __construct(Sliders $model)
    {
    	$this->model = $model;
        $this->upload_path = 'img'.DIRECTORY_SEPARATOR.'sliders'.DIRECTORY_SEPARATOR;
        $this->storage = Storage::disk('public');
    }
    /**

     *
     * @return mixed
     */
    public function getForDataTable()
    {
        /**
         * Note: You must return deleted_at or the Sliders getActionButtonsAttribute won't
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
            // Uploading image
    		if (array_key_exists('image', $input)) {
    			$input=$this->uploadImage($input);
    		}

    		if (Sliders::create($input)) {
    			return true;
    		}

    		throw new GeneralException('There was a problem creating. Please try again.');
    	});
    }

    /**
     * @param Model $Sliders
     * @param $request
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function update($Sliders, $request,$id)
    {

    	DB::transaction(function () use ($Sliders, $request,$id) {

            // Uploading image
            if (array_key_exists('image', $request)) {
                $request=$this->uploadImage($request);
            }

            if ($Sliders->update($request)) {
             return true;
         }

         throw new GeneralException('There was a problem in updating. Please try again.');
     });
    }


    /**
     * Delete Sliders.
     *
     * @param Model $Sliders
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete($Sliders)
    {

    	if ($Sliders->delete()) {
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


    	if (isset($input['image']) && !empty($input['image']))
    	{
    		$image=$input['image'];

    		$fileName = $image->getClientOriginalName();

    		$this->storage->put($this->upload_path.$fileName, file_get_contents($image->getRealPath()));

    		$input['image'] = $fileName;
    		return $input;
    	}
    }
}
