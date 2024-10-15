<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('style.css')}}">

    <style>
        .hidden-row {
            display: none;
        }
    </style>
    <script>
        function toggleProducts(id) {
            var row = document.getElementById(id);
            if (row.style.display === "none") {
                row.style.display = "table-row";
            } else {
                row.style.display = "none";
            }
        }
    </script>

</head>
<body>
    <a href="{{ route('create_form') }}">Adicionar XML</a>
    <br>
    @isset($notasFiscais)  
        <table border="1" width="100%">
            <thead>
                <tr>
                    <th width="20%">Emitente</th>
                    <th width="5%">Nota Fiscal</th>
                    <th width="5%">Emissao</th>
                    <th width="5%">Total</th>
                    <th width="10%">Acoes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($notasFiscais as $nf)
                    <!-- Linha da compra 1 -->
                    <tr>
                        <td>{{ $nf['xNomeEmit'] }} {{ $nf['chave'] }}</td>
                        <td>{{ $nf['numNf'] }}</td>
                        <td>{{ $nf['emissao'] }}</td>
                        <td>{{ $nf['totalNf'] }}</td>
                        <td>
                            <!-- Ícone para exibir produtos -->
                            <button onclick="toggleProducts('produto-{{ $loop->iteration }}')">
                                <img src="https://img.icons8.com/ios-glyphs/30/000000/expand-arrow.png" alt="Expandir Produtos">
                            </button>
                        </td>
                    </tr>
                    <!-- Linha oculta com os produtos da compra 1 -->
                    <tr id="produto-{{ $loop->iteration }}" class="">
                        <td colspan="5">
                            <table border="1">
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Descricao</th>
                                        <th>NCM</th>
                                        <th>CFOP</th>
                                        <th>Unidade</th>
                                        <th>Quantidade</th>
                                        <th>Valor Unitario</th>
                                        <th>Valor Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($nf['produtos'] as $produto)
                                        <tr>
                                            <td>{{ $produto['cod'] }}</td>
                                            <td>{{ $produto['xProd'] }}</td>
                                            <td>{{ $produto['ncm'] }}</td>
                                            <td>{{ $produto['cfop'] }}</td>
                                            <td>{{ $produto['unidade'] }}</td>
                                            <td>{{ $produto['quantidade'] }}</td>
                                            <td>{{ $produto['vlUnit'] }}</td>
                                            <td>{{ $produto['vlTotal'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach

        </table>
    @else 
        <p>Nao ha notas fiscais para mostrar</p>
    @endisset

</body>
</html>