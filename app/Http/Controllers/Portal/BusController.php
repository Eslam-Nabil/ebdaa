<?php

namespace App\Http\Controllers\Portal;

use Auth;
use Validator;
use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Driver;
use App\Models\Journey;
use App\Models\StudentToBus;
use App\Models\StudentToJourney;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BusController extends Controller
{
    public function index(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) 
        {
            return redirect()->route('portal.applications');
        }

        $drivers = Driver::all();

        $buses = Bus::with(['driver', 'students.student'])->get();

        $journeys = Journey::with(['driver', 'bus', 'students.student'])->get();

        return view('portal/buses/index', [
            'drivers' => $drivers,
            'buses' => $buses,
            'journeys' => $journeys,
        ]);

    }

    // Bus
    public function AddBus(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) 
        {
            return redirect()->route('portal.applications');
        }

        $credentials = $request->all();
        $rules = [
            'seats' => 'required',
            'RN' => 'required',
            'driver' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $bus = new Bus;
        $bus->seats = $credentials['seats'];
        $bus->RN = $credentials['RN'];
        $bus->driver_id = $credentials['driver'];
        $bus->save();

        return response()->json($bus);
    }

    public function RemoveBus(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) 
        {
            return redirect()->route('portal.applications');
        }

        $credentials = $request->all();
        $rules = [
            'id' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = Bus::find($credentials['id'])->delete();

        return response()->json($data);
    }

    public function UpdateBus(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) 
        {
            return redirect()->route('portal.applications');
        }

        $credentials = $request->all();
        $rules = [
            'id' => 'required',
            'seats' => 'required',
            'RN' => 'required',
            'driver' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $bus = Bus::find($credentials['id']);
        $bus->seats = $credentials['seats'];
        $bus->RN = $credentials['RN'];
        $bus->driver_id = $credentials['driver'];
        $bus->save();

        return response()->json($bus);
    }

    // Driver
    public function AddDriver(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) 
        {
            return redirect()->route('portal.applications');
        }

        $credentials = $request->all();
        $rules = [
            'name' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $driver = new Driver;
        $driver->name = $credentials['name'];
        $driver->save();
        $driver->generateToken();

        return response()->json($driver);
    }

    public function RemoveDriver(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) 
        {
            return redirect()->route('portal.applications');
        }

        $credentials = $request->all();
        $rules = [
            'id' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = Driver::find($credentials['id'])->delete();
        return response()->json($data);
    }

    public function UpdateDriver(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) 
        {
            return redirect()->route('portal.applications');
        }

        $credentials = $request->all();
        $rules = [
            'id' => 'required',
            'name' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $driver = Driver::find($credentials['id']);
        $driver->name = $credentials['name'];
        $driver->save();

        return response()->json($driver);
    }

    public function GetDriverBus(Request $request)
    {
        $buses = Auth::user()->buses()->with(['students.student.father', 'journeys.students'])->get();
        $driver = Auth::user();
        return response()->json(['driver' => $driver, 'buses' => $buses]);
    }

    // Student
    public function getBusStudents(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) 
        {
            return redirect()->route('portal.applications');
        }

        $buses = Bus::with(['students.student.s2p.parent', 'driver'])->get();

        return view('portal/buses/students', ['buses' => $buses]);
    }

    public function AddStudentToBus(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) 
        {
            return redirect()->route('portal.applications');
        }

        $credentials = $request->all();
        $rules = [
            'bus_id' => 'required',
            'student_id' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $duplicate = StudentToBus::where('bus_id', $credentials['bus_id'])->where('student_id', $credentials['student_id'])->get();
        
        if ($duplicate->count() > 0)
        {
            return response()->json([ 'code' => 1, 'message' => "this student already in this bus."]);
        }

        $studentToBus = new StudentToBus;
        $studentToBus->bus_id = $credentials['bus_id'];
        $studentToBus->student_id = $credentials['student_id'];
        $studentToBus->save();

        return response()->json([ 'code' => 0, 'message' => ""]);
    }

    public function RemoveStudent(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) 
        {
            return redirect()->route('portal.applications');
        }

        $credentials = $request->all();
        $rules = [
            'bus_id' => 'required',
            'student_id' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $result = StudentToBus::where('bus_id', $credentials['bus_id'])->where('student_id', $credentials['student_id'])->delete();

        if ($result == 1)
        {
            return response()->json([ 'code' => 0, 'message' => ""]);
        }
        else
        {
            return response()->json([ 'code' => 1, 'message' => "Error: could not remove student"]);
        }        
    }

    public function getJourneys(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) 
        {
            return redirect()->route('portal.applications');
        }

        $journeys = Journey::with(['driver', 'bus'])->get();

        $result = [];
        foreach($journeys as $journey)
        {
            $result[] = [ 
                "bus" => isset($journey->bus) ? $journey->bus->RN : null,
                "driver" => isset($journey->driver) ? $journey->driver->name : null,
                "start" => $journey->started,
                "end" => $journey->ended,
            ];
        }

        return response()->json(["data" => $result]);
    }

    public function Journeys(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) 
        {
            return redirect()->route('portal.applications');
        }

        return view('portal/buses/journeys');
    }

    public function StartJourney(Request $request)
    {
        $credentials = $request->all();
        $rules = [
            'lat' => 'required',
            'lng' => 'required',
            'bus' => 'required',
            'route' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return abort(400, 'Invalid Parameters');
        }

        $journey = new Journey();
        $journey->bus_id = $credentials['bus'];
        $journey->driver_id = Auth::user()->id;
        $journey->started = Carbon::now();
		$journey->ended = null;
        $journey->start_lng = $credentials['lng'];
        $journey->start_lat = $credentials['lat'];
        $journey->current_lat = $credentials['lat'];
        $journey->current_lng = $credentials['lng'];
        $journey->route = $credentials['route'];
        $journey->save();

        return response()->json($journey);
    }

    public function StopJourney(Request $request)
    {
        $credentials = $request->all();
        $rules = [
            'lat' => 'required',
            'lng' => 'required',
            'id' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return abort(400, 'Invalid Parameters');
        }

        $journey = Journey::find($credentials['id']);
        $journey->current_lat = $credentials['lat'];
        $journey->current_lng = $credentials['lng'];
        $journey->ended = Carbon::now();
        foreach($journey->students() as $student)
        {
            $student->out_lat = $credentials['lat'];
            $student->out_lng = $credentials['lng'];
            $student->save();
        }
        $journey->save();
        $journey->delete();

        return response()->json($journey);
    }

    public function UpdateJourney(Request $request)
    {
        $credentials = $request->all();
        $rules = [
            'lat' => 'required',
            'lng' => 'required',
            'id' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return abort(400, 'Invalid Parameters');
        }

        $journey = Journey::find($credentials['id']);
        $journey->current_lat = $credentials['lat'];
        $journey->current_lng = $credentials['lng'];
        $journey->save();

        return response()->json($journey);
    }

    public function AddStudentToJourney(Request $request)
    {
        $credentials = $request->all();
        $rules = [
            'lat' => 'required',
            'lng' => 'required',
            'id' => 'required',
            'student' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return abort(400, 'Invalid Parameters');
        }

        $studentToJourney = new StudentToJourney();
        $studentToJourney->student_id = $credentials['student'];
        $studentToJourney->journey_id = $credentials['id'];
        $studentToJourney->in_lat = $credentials['lat'];
        $studentToJourney->in_lng = $credentials['lng'];
        $studentToJourney->save();

        return response()->json($studentToJourney);
    }

    public function RemoveStudentFromJourney(Request $request)
    {
        $credentials = $request->all();
        $rules = [
            'lat' => 'required',
            'lng' => 'required',
            'id' => 'required',
			'student' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return abort(400, 'Invalid Parameters');
        }

        $studentToJourney = StudentToJourney::where('journey_id', $credentials['id'])->where('student_id', $credentials['student'])->first();
        $studentToJourney->out_lat = $credentials['lat'];
        $studentToJourney->out_lng = $credentials['lng'];
        $studentToJourney->save();

        return response()->json($studentToJourney);
    }
}
