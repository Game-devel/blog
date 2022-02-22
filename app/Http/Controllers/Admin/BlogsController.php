<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Blogs;

class BlogsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    protected $limit = 20;

    public function index($page = 1, $rubric_id = null)
    {
        return view('admin.blogs.list', [
            'page' => $page,
            'rubric_id' => $rubric_id
        ]);
    }

    public function getBlogsList($page = 1, $rubric_id = null, Request $request)
    {
        $keyword = $request->input('s');
        $blogs = [];
        $page_list = [];

        $page--;
        $offset = $page * $this->limit;
        list($blogs, $page_list) = $this->getBlogsAll($offset, $page, $rubric_id, $keyword);

        if (count($page_list) == 0) {
            $page_list = [1];
        }

        return response()->json([
            'list' => $blogs,
            'page' => ++$page,
            'page_list' => $page_list
        ]);

    }

    public function create()
    {
        $blogs = new Blogs();
        return view('admin.blogs.add', compact('blogs'));
    }

    public function store(Request $request)
    {
        try {
            $data = (array)json_decode($request->list);
            $validate = Blogs::validator($data);
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors(), 'success' => false], 422);
            }
            $result = Blogs::create($data);
            
            if ($request->file('image')) {
                $file = $request->file('image');
                $extension = strtolower($file->extension());
                $name = "image.{$extension}";
                $result->image = $name;
                $result->save();
                $path = "/blogs/blog_{$result->id}/";

                Storage::makeDirectory($path, 0777, true);
                Storage::disk('local')->put($path . $name, file_get_contents($file->getRealPath()));
                $full_path = public_path() . '/uploads' . $path.$name;
                exec("cwebp -q 70 {$full_path} -o {$full_path}.webp");
            }

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 422);
        }

    }

    public function getBlogSingle($id)
    {
        $blogs = Blogs::find($id);

        return response()->json($blogs);
    }

    public function show($id)
    {
        return view('admin.blogs.single', ['id' => $id]);
    }

    public function edit($id)
    {
        return view('admin.blogs.edit', ['id' => $id]);
    }

    public function update($id, Request $request)
    {
        try {
            $data = (array)json_decode($request->list);
            $blogs = Blogs::find($id);

            if ($request->file('image')) {
                $file = $request->file('image');
                $extension = strtolower($file->extension());
                $name = "image.{$extension}";
                $data['image'] = $name;
                $path = "/blogs/blog_{$blogs->id}/";

                Storage::deleteDirectory($path);
                Storage::makeDirectory($path, 0777, true);
                Storage::disk('local')->put($path . $name, file_get_contents($file->getRealPath()));
                $full_path = public_path() . '/uploads' . $path.$name;
                exec("cwebp -q 70 {$full_path} -o {$full_path}.webp");
            }

            $blogs->update($data);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 422);
        }
    }

    public function destroy($id)
    {
        try {
            Blogs::where('id', $id)->delete();

            return response()->json([], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 422);
        }

    }

    public function getBlogsAll($offset = 0, $page = 0, $rubric_id = null, $keyword = null)
    {
        $page_list = [];
        $pages = ceil(
                Blogs::when($rubric_id, function ($query) use ($rubric_id) {
                    return $query->where('rubric_id', $rubric_id);
                })->when($keyword, function ($query) use ($keyword) {
                    return $query->where('name', 'like', '%'.$keyword.'%');
                })->count() / $this->limit);

        for ($i = $page + 1; $i < $page + 10 && $i <= $pages; $i++) {
            $page_list[] = $i;
        }

        $blogs = Blogs::when($rubric_id, function ($query) use ($rubric_id) {
            return $query->where('rubric_id', $rubric_id);
        })->when($keyword, function ($query) use ($keyword) {
            return $query->where('name', 'like', '%'.$keyword.'%');
        })->limit($this->limit)->offset($offset)->orderBy('id', 'desc')->get();

        return [
            $blogs,
            $page_list
        ];
    }
}
