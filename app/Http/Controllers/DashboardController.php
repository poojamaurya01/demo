<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Topic;
use DataTables;

class DashboardController extends Controller
{
    
    public function index()
    {  
        $topics = Topic::all();
        return view('welcome', compact('topics'));
    }
    public function getCoursesDataTable(Request $request)
    {
        // dd($request->all());
        $query = Course::query();
        
        if ($request->has('order_by')) {
            $order_by = $request->input('order_by');
            $query->orderBy('id', $order_by);
        }
            
        if ($request->has('selected_topics')) {
            $topics = $request->input('selected_topics');
            $query->whereHas('topic', function ($topicQuery) use ($topics) {
                $topicQuery->whereIn('name', $topics);
            });
        }
    
        if ($request->has('price_range')) {
            $priceRange = $request->input('price_range');
            $priceRange = array_map('floatval', $priceRange);
            if(count($priceRange) >= 2 )
            {
                $query->whereBetween('price_range', $priceRange);
            }
            else
            {
                $query->where('price_range', '<' , $priceRange[0]);
            }

        } else {
            if ($request->has('min_price') && !empty($request->input('min_price')) && $request->has('max_price') && !empty($request->input('max_price')) ) {
                $minPrice = (float) $request->input('min_price');
                $maxPrice = (float) $request->input('max_price');
                $query->whereBetween('price_range', [$minPrice, $maxPrice]);
            } elseif ($request->has('min_price')) {
                $minPrice = (float) $request->input('min_price');
                $query->where('price_range', '>=', $minPrice);
            } elseif ($request->has('max_price')) {
                $maxPrice = (float) $request->input('max_price');
                $query->where('price_range', '<=', $maxPrice);
            }
        }
    
        return DataTables::of($query)
            ->addColumn('topic_name', function ($course) {
                return $course->topic->name;
            })
            ->rawColumns(['topic_name'])
            ->make(true);
    }
    
    
}
