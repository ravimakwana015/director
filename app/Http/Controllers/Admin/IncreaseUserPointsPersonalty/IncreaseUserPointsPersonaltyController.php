<?php
namespace App\Http\Controllers\Admin\IncreaseUserPointsPersonalty;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use App\User;
use App\Models\Like\Like;
use App\Models\UsersPersonalityTraits\UsersPersonalityTraits;
use Throwable as ThrowableAlias;

class IncreaseUserPointsPersonaltyController extends Controller
{
    /**
     * @return View
     */
    public function increaseUserLikesForm()
    {
        $users = User ::where('status', 1) -> get();
        return view('admin.increaseUserPoint.likes', compact('users'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function increaseUserLikes(Request $request)
    {
        $validatedData = $request -> validate([
            'user_id' => 'required',
            'like' => 'required|numeric',
        ], [
            'user_id.required' => 'Please Select User',
        ]);
        $input = $request -> all();
        $ip = request() -> ip();
        Like ::create([
            'user_id' => $input['user_id'],
            'profile_id' => $input['user_id'],
            'like_user_type' => 'admin_reward_point',
            'ip_address' => $ip,
            'count' => $input['like']
        ]);
        return redirect() -> back() -> with('success', 'Likes added Successfully.');
    }

    public function getUserLikes(Request $request)
    {
        $input = $request -> all();
        return likeCount($input['user_id']);
    }

    /**
     * @return View
     */
    public function increaseUserPersonalityForm()
    {
        $users = User ::where('status', 1) -> get();
        return view('admin.increaseUserPoint.personality', compact('users'));
    }

    public function increaseUserPersonality(Request $request)
    {
        $validator = Validator ::make($request -> all(), [
            'user_id' => 'required',
            'loneliness' => 'sometimes|required|numeric|max:200',
            'entertainment' => 'sometimes|required|numeric|max:200',
            'curiosity' => 'sometimes|required|numeric|max:200',
            'relationship' => 'sometimes|required|numeric|max:200',
            'hookup' => 'sometimes|required|numeric|max:200',
        ], [
            'user_id.required' => 'Please Select User',
            'loneliness.required' => 'Determination is required',
            'entertainment.required' => 'Genre Flexibility is required',
            'curiosity.required' => 'Communication is required',
            'relationship.required' => 'Work Ethic is required',
            'hookup.required' => 'Honesty is required',
            'loneliness.numeric' => 'Determination must be numeric',
            'entertainment.numeric' => 'Genre Flexibility must be numeric',
            'curiosity.numeric' => 'Communication must be numeric',
            'relationship.numeric' => 'Work Ethic must be numeric',
            'hookup.numeric' => 'Honesty must be numeric',
            'loneliness.max' => 'Determination must be less than or equal to 200',
            'entertainment.max' => 'Genre Flexibility must be less than or equal to 200',
            'curiosity.max' => 'Communication must be less than or equal to 200',
            'relationship.max' => 'Work Ethic must be less than or equal to 200',
            'hookup.max' => 'Honesty must be less than or equal to 200',
        ]);
        if ($validator -> fails()) {
            return Response ::json(array(
                'success' => false,
                'errors' => $validator -> getMessageBag() -> toArray()
            ), 400);
        }
        $input = $request -> all();
        $personality = UsersPersonalityTraits ::where('user_id', $input['user_id']) -> first();
        $personality -> update($input);

        return Response ::json(array(
            'success' => true,
            'errors' => ['User Personality Traits added Successfully']
        ), 200);
    }

    /**
     * @param Request $request
     * @return
     * @throws ThrowableAlias
     */
    public function getUserPersonality(Request $request)
    {
        $validator = Validator ::make($request -> all(), [
            'user_id' => 'required',
        ], [
            'user_id.required' => 'Please Select User',
        ]);
        if ($validator -> fails()) {
            return Response ::json(array(
                'success' => false,
                'errors' => $validator -> getMessageBag() -> toArray()

            ), 400);
        }
        $input = $request -> all();
        $personality = UsersPersonalityTraits ::where('user_id', $input['user_id']) -> first();
        if (isset($personality)) {
            $personality = view('admin.increaseUserPoint.traits', compact('personality')) -> render();
            return Response ::json(array(
                'success' => true,
                'personality' => $personality
            ), 200);
        } else {
            return Response ::json(array(
                'success' => false,
                'errors' => ['User Personality Traits Not Available']
            ), 400);
        }
    }

}

?>
