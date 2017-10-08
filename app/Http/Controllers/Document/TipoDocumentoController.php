<?php
namespace aidocs\Http\Controllers\Document;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use aidocs\Http\Requests;
use aidocs\Http\Controllers\Controller;
use aidocs\Models\TipoDocumento;


class TipoDocumentoController extends Controller
{
    public function storeTipoDoc(Request $request)
    {
        DB::transaction(function($request) use ($request){

            $new_tipo = new TipoDocumento();

            $new_tipo->ttypDoc = $request->codTipo;
            $new_tipo->ttypDesc = $request->dscTipo;

            $new_tipo->save();
        });
    }

    public function getListTypeDocs(Request $request)
    {
        $list_docs = TipoDocumento::select('*')->get();

        $view = view('setting.create_typedoc',compact('list_docs'));

        if($request->ajax())
        {
            $sections = $view->renderSections();
            return $sections['sub-content'];
        }

        return $view;
    }

    public function postNewTypeDocs(Request $request)
    {
        DB::transaction(function($request) use ($request){

            $t_doc = new TipoDocumento();

            $t_doc->ttypDoc = $request->id_doc;
            $t_doc->ttypDesc = $request->dsc_doc;
            $t_doc->created_at = Carbon::now()->toDateString();
            $t_doc->updated_at = Carbon::now()->toDateString();

            $t_doc->save();
        });

        if($request->ajax())
        {
            return response()->json([
                'typeId'    => $request->id_doc,
                'dsc'       => $request->dsc_doc
            ]);
        }

        return false;
    }

    public function deleteTypeDocs($id, Request $request)
    {
        DB::transaction(function($id) use ($id, $request){

            $tdoc = TipoDocumento::find($id);
            $tdoc->delete();
        });

        if($request->ajax())
        {
            return response()->json([
                'id'      => $id,
                'message' => 'El tipo de documento '.$id.' fue eliminado'
            ]);
        }

        return false;
    }

    public function getUpdateShowTipo(Request $request)
    {
        DB::transaction(function($request) use ($request){

            $tipo = TipoDocumento::find($request->id);
            $tipo->ttypShow = !$request->state;
            $tipo->save();
        });

        if($request->ajax())
        {
            return response()->json([
                'id'      => $request->id,
                'message' => 'El estado del tipo '.$request->id.' fue actualizado'
            ]);
        }

        return false;
    }
}
