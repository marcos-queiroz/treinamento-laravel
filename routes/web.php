<?php

use App\Http\Controllers\PrinciapalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PrincipalController@principal')->name('site.index');
Route::get('/sobre-nos', 'SobreNosController@sobreNos')->name('site.sobrenos');
Route::get('/contato', 'ContatoController@contato')->name('site.contato');
Route::post('/contato', 'ContatoController@contato')->name('site.contato');

Route::get('/login', 'LoginController@contato')->name('site.login');

Route::prefix('/app')->group(function() {
    Route::get('/clientes', 'ClienteController@index')->name('app.clientes');
    Route::get('/fornecedores', 'FornecedorController@index')->name('app.fornecedores');
    Route::get('/produtos', 'ProdutoController@index')->name('app.produtos');
});

Route::fallback(function(){
    echo '404 | Não achou! <a href="'.route('site.index').'">Clique aqui</a> para ir para a página inicial.';
});
