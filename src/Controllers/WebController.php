<?php

namespace Nksoft\Master\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Nksoft\Master\Models\FilesUpload;
use Nksoft\Master\Models\Histories;
use Nksoft\Master\Models\UrlRedirects;
use Nksoft\Products\Models\Categories;
use Nksoft\Products\Models\CategoryProductsIndex;
use Nksoft\Products\Models\Products;
use Nksoft\Products\Models\ProfessionalRatings;
use Nksoft\Products\Models\Regions;
use Nksoft\Products\Models\TypeProducts;
use Nksoft\Products\Models\Vintages;
use Nksoft\Products\Models\VintagesProductIndex;
use Str;

class WebController extends Controller
{
    /** Variable module */
    protected $module = '';

    protected $model = CurrentModel::class;

    /** Variable exclude  */
    protected $excludeCol = ['images', 'banner', 'id', 'none_slug'];

    public function responseError($message = null)
    {
        return response()->json([
            'status' => 'error',
            'data' => null,
            'success' => false,
            'message' => $message,
        ]);
    }

    public function responseSuccess(array $data = [], $loadImage = true)
    {
        return response()->json([
            'status' => 'success',
            'message' => [
                'default' => trans('nksoft::message.Success'),
            ],
            'data' => $data,
            'media' => isset($data['formElement']) && $loadImage ? scandir(storage_path('app/public/media')) : [],
            'breadcrumb' => $this->breadcrumb(),
            'button' => trans('nksoft::common.Button'),
            'canDelete' => Auth::check() && Auth::user()->role_id == 1 ? true : false,
        ]);
    }

    public function responseViewSuccess(array $data = [], $message = [])
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => $message,
        ]);
    }

    public function SEO($result)
    {
        $image = $result->images()->first();
        $im = $image ? 'storage/' . $image->image : 'wine/images/share/logo.svg';
        $data = [
            'title' => $result->meta_title ? $result->meta_title : $result->name,
            'ogDescription' => $result->meta_description,
            'ogUrl' => url($result->slug),
            'ogImage' => url($im),
            'canonicalLink' => $result->canonical_link ? $result->canonical_link : url($result->slug),
            'ogSiteName' => $result->meta_title ? $result->meta_title : $result->name,
        ];
        return $data;
    }

    public function validateDate($date, $format = 'm/d/Y')
    {
        $d = \DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    public function status()
    {
        $status = [];
        foreach (config('nksoft.status') as $v => $k) {
            $status[] = ['id' => $k['id'], 'name' => trans('nksoft::common.' . $k['name'])];
        }
        return $status;
    }

    public function getTypeProducts()
    {
        return TypeProducts::select(['id', 'name'])->get();
    }

    public function breadcrumb()
    {
        $segment = request()->segments();
        $segment = array_slice($segment, 1, 4);
        $breadcrumb = [];
        foreach ($segment as $i => $item) {
            $link = url($item);
            if ($i > 0) {
                $link = url(implode('/', array_slice($segment, 0, 2)));
            }
            if (!intval($item)) {
                $breadcrumb[] = [
                    'title' => trans('nksoft::common.' . Str::slug($item, " ")),
                    'link' => $link,
                ];
            }

        }
        return [
            'title' => trans('nksoft::common.' . Str::slug($this->module, ' ')),
            'breadcrumb' => $breadcrumb,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master::layout', ['element' => 'list']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master::layout', ['element' => 'create']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $images = $request->file('files');
            $nameImages = [];
            foreach ($images as $file) {
                if ($file->isValid()) {
                    $extension = $file->getClientOriginalExtension();
                    $fileName = Str::slug($file->getClientOriginalName(), '-') . '-' . rand(3, time()) . '.' . $extension;
                    putUploadImage($file, $fileName);
                    array_push($nameImages, $fileName);
                }
            }
            return $this->responseSuccess(['images' => $nameImages]);
        } catch (\Exception $e) {
            return $this->responseError($e->getMessage());
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
        return view('master::layout', ['element' => 'edit']);
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
        $validator = Validator($request->all(), ['name' => 'required'], ['name.required' => __('nksoft::message.Field is require!', ['Field' => trans('nksoft::users.Username')])]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator]);
        }
        $image = FilesUpload::find($id);
        if ($image != null) {
            $image->name = $request->get('name');
            $image->order_by = $request->get('order_by');
            $image->slug = $request->get('slug');
            $image->save();
        }
        $response = $this->responseSuccess();
        return response()->json($response);
    }

    public function destroyImage(Request $request)
    {
        try {
            $images = $request->get('images');
            if (count($images) > 0) {
                foreach ($images as $image) {
                    deleteImage('public/media/' . $image);
                }
            }
            return $this->responseSuccess();
        } catch (\Exception $e) {
            return $this->responseError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyUploadFile($id)
    {
        try {
            FilesUpload::find($id)->delete();
            $response = $this->responseSuccess();
        } catch (\Exception $e) {
            $response = $this->responseError($e);
        }
        return response()->json($response);
    }

    public function media($request, $result)
    {
        $images = $request->get('images');
        if ($images) {
            $this->setMedia($images, $result->id, $this->module);
        }
        $banner = $request->get('banner');
        if ($banner) {
            $this->setMedia($banner, $result->id, $this->module, 2);
        }
        $maps = $request->get('maps');
        if ($maps) {
            $this->setMedia($maps, $result->id, $this->module, 3);
        }
    }

    /**
     * function insert media
     */
    public function setMedia($images, $parent_id, $type, $group_id = 1)
    {
        if (isset($images)) {
            $images = json_decode($images);
            $name = request()->get('name');
            foreach ($images as $img) {
                $where = ['type' => $type, 'parent_id' => $parent_id, 'group_id' => $group_id, 'image' => $img->image];
                $value = array_merge($where, ['order_by' => $img->order_by ? $img->order_by : 0, 'name' => $img->name ? $img->name : $name, 'slug' => $img->slug]);
                FilesUpload::updateOrCreate($where, $value);
            }
        }
    }

    public function getSlug(array $data)
    {
        try {
            $url = !isset($data['slug']) ? Str::slug($data['name'] . rand(100, strtotime('now')), '-') : $data['slug'];
            $url = (strpos($url, '/') !== false || strpos($url, '#') !== false) ? $data['slug'] : Str::slug($url);
            return $url;
        } catch (\Execption $e) {
            return $this->responseError($e);
        }
    }

    public function setUrlRedirects($result)
    {
        try {
            $url = !$result->slug || is_null($result->slug) ? Str::slug($result->name . rand(100, strtotime('now')), '-') : strpos($result->slug, '/') === false ? Str::slug($result->slug) : $result->slug;
            $existsUrl = UrlRedirects::where(['url_original' => $url])->first();
            if ($existsUrl) {
                return $this->responseError(trans('nksoft::common.Url exists'));
            }
            UrlRedirects::updateOrCreate(['url_path' => $this->module . '/' . $result->id], ['url_original' => $url, 'url_path' => $this->module . '/' . $result->id]);
        } catch (\Execption $e) {
            return $this->responseError($e);
        }
    }

    /**
     * function insert histories to admin delete
     */
    public function setHistories($parent_id, $type)
    {
        return Histories::firstOrCreate(['parent_id' => $parent_id, 'type' => $type, 'user_id' => Auth::user()->id]);
    }

    /**
     * function delete histories to admin delete
     */
    public function destroyHistories($parent_id, $type)
    {
        $history = Histories::where(['parent_id' => $parent_id, 'type' => $type])->first();
        if ($history) {
            return $history->delete();
        }

    }

    public function getHistories($type)
    {
        return Histories::where(['type' => $type])->get();
    }

    public function destroy($id)
    {
        try {
            if (Auth::user()->role_id == 1) {
                if (!request()->get('isCancel')) {
                    $this->model::find($id)->delete();
                }

                $this->destroyHistories($id, $this->module);
            } else {
                $this->setHistories($id, $this->module);
            }
            return $this->responseSuccess();
        } catch (\Exception $e) {
            return $this->responseError($e);
        }
    }

    public function getProductType($result)
    {
        $idSelected = $result ? json_decode($result->type) : [];
        if (!is_array($idSelected)) {
            $idSelected = [$idSelected];
        }

        $data = array();
        foreach (config('nksoft.productType') as $item) {
            $selected = array(
                'opened' => false,
                'selected' => in_array($item['id'], $idSelected) ? true : false,
            );
            $data[] = array(
                'name' => $item['name'],
                'icon' => 'fas fa-folder',
                'id' => $item['id'],
                'state' => $selected,
                'children' => null,
                'slug' => '',
            );
        }
        return $data;
    }

    public function listFilter($type, $products, $typeRemove = null)
    {
        $listFilters = array();
        $typeProducts = TypeProducts::find($type);
        $filter = array();
        $productId = $products->pluck('id');
        $regionId = $products->pluck('regions_id');
        $regionParentId = Regions::whereIn('id', $regionId)->select('parent_id')->groupBy('parent_id')->pluck('parent_id');
        if ($typeProducts) {
            $filter = json_decode($typeProducts->filter);
        }

        if (in_array(1, $filter)) {
            $c = [
                'label' => 'Theo Loại',
                'items' => Categories::select(['id', 'name'])->where(['type' => $type, 'is_active' => 1])->whereIn('id', function ($query) use ($productId) {
                    $query->from(with(new CategoryProductsIndex())->getTable())->select(['categories_id'])->whereIn('products_id', $productId)->groupBy('categories_id')->pluck('categories_id');})->get(),
                'type' => 'c',
                'icon' => 'wine',
            ];
            array_push($listFilters, $c);
        }
        if (in_array(2, $filter)) {
            $text = 'Theo Vùng';
            if (!in_array($type, [4, 1])) {
                $text = 'Theo Dòng';
            }
            $r = [
                'label' => $text,
                'items' => Regions::select(['id', 'name'])->where(['type' => $type, 'is_active' => 1])->where('parent_id', '>', 0)->whereIn('id', $regionId)->get(),
                'type' => 'r',
                'icon' => 'area',
            ];
            array_push($listFilters, $r);
        }
        if (in_array(3, $filter)) {
            $r = [
                'label' => 'Theo Giống',
                'items' => Vintages::select(['id', 'name'])->where(['type' => $type, 'is_active' => 1])->whereIn('id', function ($query) use ($productId) {
                    $query->from(with(new VintagesProductIndex())->getTable())->select(['vintages_id'])->whereIn('products_id', $productId)->pluck('vintages_id');
                })->get(),
                'type' => 'vg',
                'icon' => 'type',
            ];
            array_push($listFilters, $r);
        }
        if (in_array(4, $filter)) {
            $r = [
                'label' => 'Theo Nước',
                'items' => Regions::select(['id', 'name'])->where(['type' => $type, 'is_active' => 1])->where('parent_id', '=', 0)->whereIn('id', $regionParentId)->get(),
                'type' => 'rg',
                'icon' => 'country',
            ];
            array_push($listFilters, $r);
        }
        if (in_array(5, $filter)) {
            $r = [
                'label' => 'Theo Điểm Rượu',
                'items' => ProfessionalRatings::select(['ratings as name', 'ratings as id'])->whereIn('products_id', $productId)->groupBy('ratings')->get(),
                'type' => 'p',
                'icon' => 'star',
            ];
            array_push($listFilters, $r);
        }
        if (in_array(6, $filter)) {
            $r = [
                'label' => 'Theo Dung Tích',
                'items' => Products::select(['volume as name', 'volume as id'])->where(['type' => $type, 'is_active' => 1])->whereIn('id', $productId)->groupBy('volume')->get(),
                'type' => 'v',
                'icon' => 'size',
            ];
            array_push($listFilters, $r);
        }
        $price = [
            'label' => 'Theo khoảng giá',
            'items' => collect([
                ['id' => '0-500', 'name' => 'Dưới 500.000đ'],
                ['id' => '500-1000', 'name' => 'Từ 500.000 đến 1.000.000'],
                ['id' => '1000-2500', 'name' => 'Từ 1.000.000 đến 2.500.000'],
                ['id' => '2500-5000', 'name' => 'Từ 2.500.000 đến 5.000.000'],
                ['id' => '5000', 'name' => 'Trên 5.000.000'],
            ])->all(),
            'type' => 'pr',
        ];
        array_push($listFilters, $price);
        if ($typeRemove) {
            $listFilters = array_filter($listFilters, function ($item) use ($typeRemove) {
                return $typeRemove == 'r' ? $item['type'] != $typeRemove && $item['type'] != 'rg' : $item['type'] != $typeRemove;
            });
        }
        return $listFilters;
    }

}
