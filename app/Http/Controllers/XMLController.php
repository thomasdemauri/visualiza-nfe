<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Thomas\NFe\ReaderNFe;

class XMLController extends Controller
{
    /**
     * Essa versao inicial trata de salvar apenas algumas 
     * informacoes no banco de dados referente a NF-e e nao todas!
     * Apenas as principais que utilizaremos na loja.
     */

    /**
     * Exibe o form de upload dos arquivos.
     */
    public function create(): View {
        return view('upload');
    }


    /**
     * Salva na pasta storage/app/public.
     */
    public function store(Request $request) {

        // $request->validate([
        //     'files.*' => 'required|file|mimes:xml|max:4096'
        // ]);

        foreach ($request->file('files') as $file) {
            if ($file->isValid()) {
                // dd($file->getClientOriginalName());
                $originalName = $file->getClientOriginalName();
                $ext = $file->getExtension();

                $filename = $originalName . $ext;

                $file->storeAs('uploads', $filename);
            }
        }

        return back()->with('success', 'Files included with success.');

        dd($request->files);
    }


    public function index(): View {
        // phpinfo();
        $notasFiscais = [];

        // Carrega todos os arquivos xmls da pasta
        $files = Storage::files('uploads');

        foreach ($files as $file) {

            $absolutePath = storage_path('app/public/' . $file);
            $xml = new ReaderNFe($absolutePath);

            $chave = $xml->xml->NFe->infNFe->attributes()['Id'];
            $numNf = $xml->obtemIdentificacao()['nNF'];
            $emissao = Carbon::parse($xml->obtemIdentificacao()['dhEmi'])->format('d/m/Y');
            $xNomeEmit = $xml->obtemEmitente()['xNome'];
            $xNomeDest = $xml->obtemDestinatario()['xNome'];
            $totalNf = $xml->obtemTotal()['ICMSTot']['vNF'];
                
            $produtos = []; 
            foreach ($xml->obtemProdutos() as $produto) {
                if (isset($produto['prod']['cProd'])) {
                    array_push($produtos, [
                        'cod' => $produto['prod']['cProd'],
                        'xProd' => $produto['prod']['xProd'],
                        'ncm' => $produto['prod']['NCM'],
                        'cfop' => $produto['prod']['CFOP'],
                        'unidade' => $produto['prod']['uCom'],
                        'quantidade' => $produto['prod']['qCom'],
                        'vlUnit' => $produto['prod']['vUnCom'],
                        'vlTotal' => $produto['prod']['vProd']
                    ]);
                }
            }

            $notaFiscal = [
                'chave' => $chave,
                'numNf' => $numNf,
                'emissao' => $emissao,
                'xNomeEmit' => $xNomeEmit,
                'xNomeDest' => $xNomeDest,
                'totalNf' => $totalNf,
                'produtos' => $produtos
            ];
            
            array_push($notasFiscais, $notaFiscal);
        }

        $datas = array_column($notasFiscais, 'emissao');
        array_multisort($datas, SORT_DESC, $notasFiscais);
        // dd($notasFiscais);
        return view('home', [ 'notasFiscais' => $notasFiscais ]);
    }
}
