<?php

namespace App\Http\Controllers\Web;
use App\Models\Showroom;
use App\Models\Car;
use App\Models\ShowroomReviews;
use App\Models\ShowroomReviewRatings;
use App\Http\Controllers\Controller;
use App\Models\CarOffer;
use Exception;
use Illuminate\Http\Request;

class ShowroomReviewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $data['showroom_id'] = $id;
        $data['review_title'] = '';
        $data['review_description'] = '';

        return view('front.showroom-reviews.showroom-write-review')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'dealing_rating' => 'required',
            'selection_rating' => 'required',
            'service_rating' => 'required',
            'review_title'  => 'required',
            'review_description'  => 'required'
        ]);

        try{



            $showroom_id = $request->input('showroom_id') ? $request->input('showroom_id') : 1;
            $review_title = $request->input('review_title') ? $request->input('review_title') : '';
            $review_description = $request->input('review_description') ? $request->input('review_description') : '';

            $showroom_review= new ShowroomReviews;

            $user_id = null;
            if (session()->exists('user')) {
                $user_id = Session('user')->id;
            }

            $avg_rating = ( $request->input('dealing_rating') + $request->input('selection_rating') + $request->input('service_rating') ) / 3;

            $showroom_review->user_id = $user_id;
            $showroom_review->review_type = 'OVERALL';
            $showroom_review->review_rating = round($avg_rating,1);
            $showroom_review->showroom_id = $showroom_id;
            $showroom_review->review_title = $review_title;
            $showroom_review->review_description = $review_description;
            $showroom_review->save();

            $showroom_review_rating = new ShowroomReviewRatings;
            $showroom_review_rating->showroom_id = $showroom_id;
            $showroom_review_rating->showroom_review_id = $showroom_review->id;
            $showroom_review_rating->rating_value = $request->input('dealing_rating');
            $showroom_review_rating->rating_type = 'DEALING';
            $showroom_review_rating->save();

            $showroom_review_rating = new ShowroomReviewRatings;
            $showroom_review_rating->showroom_id = $showroom_id;
            $showroom_review_rating->showroom_review_id = $showroom_review->id;
            $showroom_review_rating->rating_value = $request->input('selection_rating');
            $showroom_review_rating->rating_type = 'SELECTION';
            $showroom_review_rating->save();

            $showroom_review_rating = new ShowroomReviewRatings;
            $showroom_review_rating->showroom_id = $showroom_id;
            $showroom_review_rating->showroom_review_id = $showroom_review->id;
            $showroom_review_rating->rating_value = $request->input('service_rating');
            $showroom_review_rating->rating_type = 'SERVICE';
            $showroom_review_rating->save();

            $showroom_avg_rating = ShowroomReviews::avg('review_rating');

            Showroom::where('id',$showroom_id)->update([
                'rating' => round($showroom_avg_rating)
            ]);

            return redirect('/showroom-reviews-detail/'.$request->input('showroom_id'))->with('notify_success', 'Showroom Review Added');
        }catch(Exception $e)
        {
            return redirect()->back()->with('notify_error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function MyShowroomDetails($id)
    {
        if (!session()->exists('user')) {
            return redirect()->route('landing')->with('notify_error', 'Login to proceed');
        }
        $user_id = Session('user')->id;
        $showRoom = Showroom::where('user_id', $user_id)->first();
        if (empty($showRoom)) {
            return redirect()->route('landing')->with('notify_error', 'This is not your showroom');
        }

        $data['showroom'] = $showRoom;
        $showroom_cars = Car::where('showroom_id', $id)->get();
        $carIds = array();
        foreach($showroom_cars as $data){
           array_push($carIds, $data->id);
        }
        $data['showroom_car_offers'] = CarOffer::whereIn('car_id',$carIds )->where('status', 'PENDING')->where('counter_amount', NULL)->count();
        $data['active_cars'] = Car::where('showroom_id', $id)->where('status','APPROVED')->get();
        $data['pending_cars'] = Car::where('showroom_id',  $id )->whereIn('status',['PENDING','REJECTED'])->get();
        $data['sold_cars'] = Car::where('showroom_id', $id)->where('status','SOLD')->get();
        return view('front.my-showroom-cars')->with('data',$data);
    }
    public function MyShowroom($id)
    {
        if (!session()->exists('user')) {
            return redirect()->route('landing')->with('notify_error', 'Login to proceed');
        }
        $user_id = Session('user')->id;
        $showRoom = Showroom::where('id', $id)->where('user_id', $user_id)->first();
        if (empty($showRoom)) {
            return redirect()->route('landing')->with('notify_error', 'This is not your showroom');
        }

        $data['showroom'] = $showRoom;
        $showroom_id =  $data['showroom']->id;
        $data['active_cars'] = Car::where('showroom_id',  $showroom_id )->where('status','APPROVED')->get();
        $data['pending_cars'] = Car::where('showroom_id',  $showroom_id )->whereIn('status',['PENDING','REJECTED'])->get();
        $data['sold_cars'] = Car::where('showroom_id',  $showroom_id )->where('status','SOLD')->get();
        return view('front.my-showroom-cars')->with($data);
    }

    public function MyshowroomEditYear(Request $request){
        Showroom::where('id',$request->id )->update(['service_year' =>  $request->year_of_service]);
        return redirect('my-showroom-details/'.$request->id);
    }
}
