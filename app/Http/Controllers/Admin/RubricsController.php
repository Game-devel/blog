<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Rubric;

class RubricsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    protected $limit = 20;
    protected $list_field = ['id', 'name'];

    public function getAll() {
        $rubrics = Rubric::all();

        return response()->json($rubrics);
    }

    public function index($page = 1)
    {
        $page--;
        return view('admin.rubric.list', ['page' => ++$page]);
    }

    public function getList($page = 1, Request $request)
    {
        $keyword = $request->input('s');
        $rubrics = [];
        $page_list = [];

        $page--;
        $offset = $page * $this->limit;
        list($rubrics, $page_list) = $this->getRubricsAll($offset, $page, $keyword);

        if (count($page_list) == 0) {
            $page_list = [1];
        }

        return response()->json([
            'list' => $rubrics,
            'page' => ++$page,
            'page_list' => $page_list
        ]);

    }

    public function create()
    {
        $blogs = new Rubric();
        return view('admin.rubric.create', compact('blogs'));
    }

    public function store(Request $request)
    {
        try {
            $data = (array)json_decode($request->list);

            $validate = Rubric::validator($data);
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors(), 'success' => false], 400);
            }
            $result = Rubric::create($data);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 422);
        }
    }

    public function getRubricSingle($id)
    {
        $rubrics = Rubric::find($id);

        return response()->json($rubrics);
    }

    public function edit($id)
    {
        return view('admin.rubric.edit', ['id' => $id]);
    }

    public function update($id, Request $request)
    {
        try {
            $data = (array)json_decode($request->list);
            $rubrics = Rubric::find($id);

            $rubrics->update($data);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 422);
        }
    }

    public function destroy($id)
    {

        try {
            Rubric::where('id', $id)->delete();

            return response()->json([], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 422);
        }
    }

    public function getRubricsAll($offset = 0, $page = 0, $keyword = null)
    {
        $page_list = [];
        $pages = ceil(
                Rubric::when($keyword, function ($query) use ($keyword) {
                    return $query->where('name', 'like', '%'.$keyword.'%');
                })->count() / $this->limit);

        for ($i = $page + 1; $i < $page + 10 && $i <= $pages; $i++) {
            $page_list[] = $i;
        }

        $rubrics = Rubric::select($this->list_field)->when($keyword, function ($query) use ($keyword) {
            return $query->where('name', 'like', '%'.$keyword.'%');
        })->limit($this->limit)->offset($offset)->orderBy('id', 'desc')->get();

        return [
            $rubrics,
            $page_list
        ];
    }
}
