<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client as GuzzleClient;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Company\Company;
use App\Models\Company\Rol;
use App\Models\Usuarios\NivelEstudios;
use App\Models\Company\Areas;
use App\Models\Company\AreaOther;
use App\Models\Company\Pais;
use App\Models\Company\Ciudades;
use App\Models\Company\Region;

use Validator;
use GuzzleHttp\Client;
use Sentinel;
use Activation;

class ApiController extends Controller
{
    protected $client;
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://172.16.100.10:8002', // tu API interna
            'timeout' => 10,
        ]);
    }

    public function apiLogin(Request $request)
    {
        if (Sentinel::authenticate($request->all())) {
            $success = [Sentinel::getUser()->name];
            $success['token'] = str_random(50);

            return response()->json([
                'login' => $success,
                'state' => 'User login successfully.',
            ]);
        } else {
            return response()->json([
                'state' => 'User login Unauthorised.',
            ]);
        }
    }

    public function apiCreate(Request $request)
    {
        $cia = $request->company_id;

        if ($cia == 1390) {
            //siesa
            $id_company = $request->company_id;
            $email = $request->email;
            $password = $request->num_documento;
            $name = $request->name;
            $last_name = $request->last_name;
            $telefono = $request->telefono;
            $direccion = $request->direccion;
            $documento = $request->num_documento;
            $cargo = $request->cargo;
            $genero = $request->genero;
            $estrato = $request->estrato;
            $estudios = $request->nivel_estudio;
            $tipo_documento = $request->tipo_documento;
            $token[] = str_random(50);
        } else {
            //buck
            $id_company = $request->company_id;
            $email = $request->email;
            $password = $request->rut;
            $name = $request->first_name;
            $last_name = $request->surname . $request->second_surname;
            $telefono = $request->phone;
            $direccion = $request->address;
            $documento = $request->rut;
            $cargo = $request->cargo;
            $genero = $request->gender;
            $estrato = $request->estrato;
            $estudios = $request->estudio;
            $tipo_documento = $request->document_type;
            $token[] = str_random(50);
        }

        $verificarD = \DB::table('users')
            ->select('company_id', 'email', 'num_documento')
            ->where([['company_id', $id_company], ['num_documento', $documento]])
            ->get();

        $cedula = '';

        foreach ($verificarD as $value) {
            $cedula = $value->num_documento;
        }

        $verificarE = \DB::table('users')
            ->select('company_id', 'email', 'num_documento')
            ->where([['company_id', $id_company], ['email', $email]])
            ->get();

        $correo = '';

        foreach ($verificarE as $value) {
            $correo = $value->email;
        }

        if ($cedula == $documento) {
            return response()->json([
                'token' => 'false',
                'state' => 'The cedula is already registered.',
            ]);
        } elseif ($correo == $email) {
            return response()->json([
                'token' => 'false',
                'state' => 'The email is already registered.',
            ]);
        } else {
            $user = Sentinel::registerAndActivate([
                'email' => $email,
                'password' => $password,
                'company_id' => $id_company,
                'name' => $name,
                'last_name' => $last_name,
                'telefono' => $telefono,
                'direccion' => $direccion,
                'tipo_documento' => $tipo_documento,
                'num_documento' => $documento,
                'cargo' => $cargo,
                'genero' => $genero,
                'estrato' => $estrato,
                'nivel_estudio' => $estudios,
                'permissions' => $token,
                'role_id' => 10,
                'estado' => 1,
            ]);
            $role = Sentinel::findRoleById(10);
            $role->users()->attach($user);
        }
        return response()->json([
            'token' => $token,
            'state' => 'User create successfully.',
        ]);
    }

    public function apiUpdateUser(Request $request)
    {
        $documento = $request->num_documento;

        $verificarD = \DB::table('users')->select('id', 'num_documento', 'permissions')->where('num_documento', $documento)->get();
        $id = '';
        foreach ($verificarD as $value) {
            $id = $value->id;
            $token = $value->permissions;
        }

        if ($id == '') {
            return response()->json([
                'token' => 'false',
                'state' => 'The cedula is not already registered.',
            ]);
        }

        $user = User::find($id);
        $user->email = $request->email;
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->direccion = $request->direccion;
        $user->telefono = $request->telefono;
        $user->tipo_documento = $request->tipo_documento;
        $user->num_documento = $request->num_documento;
        $user->genero = $request->genero;
        $user->estrato = $request->estrato;
        $user->nivel_estudio = $request->nivel_estudio;
        $user->cargo = $request->cargo;
        if ($request->password != null) {
            $user->password = bcrypt($request->password);
        }
        $user->estado = $request->estado;
        $user->save();

        return response()->json([
            'token' => $token,
            'state' => 'User update successfully.',
        ]);
    }

    //API BUK

    public function listUserApiBuk($id_company)
    {
        return view('buk/bukUser', compact('id_company'));
    }

    public function pageUsersBuk($id_company)
    {
        $ciaBuk = DB::table('apiBuk')
            ->select('id_company', 'url', 'token', 'estado')
            ->where([['id_company', $id_company], ['estado', 1]])
            ->get();

        foreach ($ciaBuk as $value) {
            $urlBuk = $value->url;
            $token = $value->token;
        }

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'auth_token' => $token,
        ];

        $client = new GuzzleClient([
            'headers' => $headers,
        ]);

        $url = $urlBuk;
        $buk = $client->request('GET', $url . 'api/v1/colombia/employees?page');
        $response = $buk->getBody()->getContents();

        $data = json_decode($response, true);
        $page = $data['pagination'];
        $total_pages = $page['total_pages'];

        return json_encode($total_pages);

        /*  for ($i=0; $i <= $total_pages; $i++) {
  
  $url=$urlBuk;
  $buk=$client->request('GET',$url.'api/v1/colombia/employees?page='.$i);
  $response = $buk->getBody()->getContents();

  }*/

        // return $response;
    }

    public function apiUserBuk(Request $request, $id_company)
    {
        $ciaBuk = DB::table('apiBuk')
            ->select('id_company', 'url', 'token', 'estado')
            ->where([['id_company', $id_company], ['estado', 1]])
            ->get();

        foreach ($ciaBuk as $value) {
            $urlBuk = $value->url;
            $token = $value->token;
        }

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'auth_token' => $token,
        ];

        $client = new GuzzleClient([
            'headers' => $headers,
        ]);

        $url = $urlBuk;
        $buk = $client->request('GET', $url . 'api/v1/colombia/employees?page=' . $request->pagina);
        $response = $buk->getBody()->getContents();

        $data = json_decode($response, true);
        $page = $data['pagination'];
        $total_pages = $page['total_pages'];

        //return json_encode($total_pages);

        /*  for ($i=0; $i <= $total_pages; $i++) {
  
  $url=$urlBuk;
  $buk=$client->request('GET',$url.'api/v1/colombia/employees?page='.$i);
  $response = $buk->getBody()->getContents();

  }*/

        return $response;
    }

    public function apiNewUserBuk(Request $request, $id_company)
    {
        $ciaBuk = DB::table('apiBuk')
            ->select('id_company', 'url', 'token', 'estado')
            ->where([['id_company', $id_company], ['estado', 1]])
            ->get();

        foreach ($ciaBuk as $value) {
            $urlBuk = $value->url;
            $token = $value->token;
        }

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'auth_token' => $token,
        ];

        $client = new GuzzleClient([
            'headers' => $headers,
        ]);

        $url = $urlBuk;
        $buk = $client->request('GET', $url . 'api/v1/colombia/employees?page');
        $response = $buk->getBody()->getContents();

        $decodeResponse = json_decode($response, true);

        $data = $decodeResponse['data'];
        $page = $decodeResponse['pagination'];
        $total_pages = $page['total_pages'];
        $next_pages = $page['next'];

        $companyID = $id_company;

        /*for ($i=0; $i <= $total_pages; $i++) {
  
  $url=$urlBuk;
  $buk=$client->request('GET',$url.'api/v1/colombia/employees?page'.$i);
  $response = $buk->getBody()->getContents();
  }*/

        $externalData = [];
        $dniList = [];

        foreach ($data as $jobs) {
            $objUser = [];
            $objUser['rut'] = str_replace('.', '', $jobs['rut']);
            $objUser['first_name'] = $jobs['first_name'];
            $objUser['surname'] = $jobs['surname'];
            $objUser['second_surname'] = $jobs['second_surname'];
            $objUser['document_type'] = $jobs['document_type'];
            $objUser['email'] = $jobs['email'];
            $objUser['address'] = $jobs['address'];
            $objUser['active_since'] = $jobs['active_since'];
            $objUser['phone'] = $jobs['phone'];
            $objUser['status'] = $jobs['status'];
            $objUser['gender'] = $jobs['gender'];
            $objUser['position'] = $jobs['current_job']['role']['name'];

            $queryResult = \DB::table('users')
                ->select('num_documento')
                ->where([['company_id', $companyID], ['num_documento', $objUser['rut']]])
                ->get();

            if (sizeof($queryResult) == 0) {
                array_push($externalData, $objUser, $total_pages, $next_pages);
            }
        }

        return $externalData;
    }

    public function apiCiaBuk(Request $request)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'auth_token' => '7jLYbTEPjjBKUy1Mk1MvK7Lu',
        ];

        $client = new GuzzleClient([
            'headers' => $headers,
        ]);

        $buk = $client->request('GET', 'https://prueba.buk.co/api/v1/colombia/companies');
        $response = $buk->getBody()->getContents();

        //$decodeResponse =json_encode($response);
        //$decodeResponse =json_decode($response, true);

        return $response;
    }

    public function editUserApi($dni)
    {
        $id_user = '';
        $verificarD = \DB::table('users')->select('id', 'num_documento')->where('num_documento', $dni)->get();

        foreach ($verificarD as $value) {
            $id_user = $value->id;
        }

        if ($id_user == '') {
            return response()->json([
                'Note' => 'The cedula is not already registered.',
            ]);
        } else {
            $pais = Pais::orderBy('paisnombre', 'ASC')->get();
            $ciudads = Ciudades::all();
            $depars = Region::all();
            $areas = Areas::all();
            $nivel = NivelEstudios::all();
            $companys = Company::orderBy('razonsocial', 'ASC')
                ->where(['estado' => 1])
                ->get();
            $roles = Rol::orderBy('id')
                ->where(['estado' => 1])
                ->get();
            $avatars = DB::table('avatar_users')->select('avatar_users.archive', 'avatar_users.id')->where('id_user', $id_user)->latest('id')->get();
            $api = 1;

            $users = \DB::table('users')
                ->select('company.id as id_company', 'company.razonsocial', 'users.id', 'users.company_id', 'users.name', 'users.last_name', 'users.direccion', 'users.id_ciudad', 'users.id_pais', 'users.id_region', 'users.tipo_documento', 'users.num_documento', 'users.estado', 'users.email', 'users.telefono', 'users.role_id', 'users.skype', 'users.alias_skype', 'users.fecha_nacimiento', 'users.genero', 'users.estrato', 'users.cargo', 'users.id_area', 'users.nivel_estudio', 'users.created_at', 'ciudades.idCiudad', 'ciudades.nombre', 'pais.id as id_pais', 'pais.paisnombre', 'region.idRegion', 'region.nombre as nombreRegion', 'areas.id as idAreas', 'areas.name as nameArias', 'nivelEstudios.id as id_nivel', 'nivelEstudios.nivelDesc')
                ->leftjoin('company', 'company.id', '=', 'users.company_id')
                ->leftjoin('pais', 'pais.id', '=', 'users.id_pais')
                ->leftjoin('region', 'region.idRegion', '=', 'users.id_region')
                ->leftjoin('ciudades', 'ciudades.idCiudad', '=', 'users.id_ciudad')
                ->leftjoin('nivelEstudios', 'nivelEstudios.id', '=', 'users.nivel_estudio')
                ->leftjoin('areas', 'areas.id', '=', 'users.id_area')
                ->where('users.id', $id_user)
                ->orderBy('company.razonsocial', 'ASC')
                ->get();

            return\View::make('users.editusers', compact('companys', 'users', 'roles', 'pais', 'ciudads', 'depars', 'areas', 'nivel', 'avatars', 'api'));
        }
    }

    public function createUserApi($data, $id_company)
    {
        $newData = explode(',', $data);
        $cedula = '';
        $correo = '';
        $api = 1;
        $name = $newData[1];
        $last_name = $newData[2] . '' . $newData[3];
        $dni = $newData[6];
        $password = $newData[6];
        $email = $newData[7];
        $direccion = $newData[8];
        if (isset($newData[9])) {
            $telefono = $newData[9];
        } else {
            $telefono = '';
        }
        if (isset($newData[13])) {
            $genero = $newData[13];
            if ($genero == 'M') {
                $genero = 2;
            } else {
                $genero = 1;
            }
        } else {
            $genero = '';
        }
        if (isset($newData[14])) {
            $birthday = $newData[14];
        } else {
            $birthday = '';
        }
        if (isset($newData[15])) {
            $cargo = $newData[15];
        } else {
            $cargo = '';
        }

        $verificarD = \DB::table('users')->select('id', 'num_documento', 'email')->where('num_documento', $dni)->get();

        foreach ($verificarD as $value) {
            $cedula = $value->num_documento;
        }
        if ($cedula == '') {
            $cedula = 0;
        } else {
            $cedula = $cedula;
        }

        $verificarC = \DB::table('users')->select('id', 'num_documento', 'email')->where('email', $email)->get();

        foreach ($verificarC as $value) {
            $correo = $value->email;
        }

        if ($correo == '') {
            $correo = 'email@noexiste.com';
        } else {
            $correo = $correo;
        }

        if ($dni == $cedula) {
            return response()->view('errors.208', compact('api', 'id_company'));
        } elseif ($email == $correo) {
            return response()->view('errors.209', compact('api', 'id_company'));
        } else {
            $user = Sentinel::registerAndActivate([
                'email' => $email,
                'password' => $password,
                'name' => $name,
                'last_name' => $last_name,
                'direccion' => $direccion,
                'telefono' => $telefono,
                'tipo_documento' => 13,
                'num_documento' => $dni,
                'company_id' => $id_company,
                'cargo' => $cargo,
                'genero' => $genero,
                'fecha_nacimiento' => $birthday,
                'role_id' => 10,
                'estado' => 1,
                'id_pais' => 52,
            ]);

            $role = Sentinel::findRoleById(10);
            $role->users()->attach($user);
        }

        //Traemos el último id generado
        $comp = User::select('id')->orderby('created_at', 'DESC')->first();
        $id_user = $comp->id;

        $pais = Pais::orderBy('paisnombre', 'ASC')->get();
        $ciudads = Ciudades::all();
        $depars = Region::all();
        $areas = Areas::all();
        $areaOther = AreaOther::where([['id_company', '=', $id_company], ['estado', 1]])->get();
        $nivel = NivelEstudios::all();
        $companys = Company::orderBy('razonsocial', 'ASC')
            ->where(['estado' => 1])
            ->get();
        $roles = Rol::orderBy('id')
            ->where(['estado' => 1])
            ->get();
        $avatars = DB::table('avatar_users')->select('avatar_users.archive', 'avatar_users.id')->where('id_user', $id_user)->latest('id')->get();

        $users = \DB::table('users')
            ->select('company.id as id_company', 'company.razonsocial', 'users.id', 'users.company_id', 'users.name', 'users.last_name', 'users.direccion', 'users.id_ciudad', 'users.id_pais', 'users.id_region', 'users.tipo_documento', 'users.num_documento', 'users.estado', 'users.email', 'users.telefono', 'users.role_id', 'users.skype', 'users.alias_skype', 'users.fecha_nacimiento', 'users.genero', 'users.estrato', 'users.cargo', 'users.id_area', 'users.nivel_estudio', 'users.created_at', 'ciudades.idCiudad', 'ciudades.nombre', 'pais.id as id_pais', 'pais.paisnombre', 'region.idRegion', 'region.nombre as nombreRegion', 'areas.id as idAreas', 'areas.name as nameArias', 'nivelEstudios.id as id_nivel', 'nivelEstudios.nivelDesc')
            ->leftjoin('company', 'company.id', '=', 'users.company_id')
            ->leftjoin('pais', 'pais.id', '=', 'users.id_pais')
            ->leftjoin('region', 'region.idRegion', '=', 'users.id_region')
            ->leftjoin('ciudades', 'ciudades.idCiudad', '=', 'users.id_ciudad')
            ->leftjoin('nivelEstudios', 'nivelEstudios.id', '=', 'users.nivel_estudio')
            ->leftjoin('areas', 'areas.id', '=', 'users.id_area')
            ->where('users.id', $id_user)
            ->orderBy('company.razonsocial', 'ASC')
            ->get();

        return\View::make('users.editusers', compact('companys', 'users', 'roles', 'pais', 'ciudads', 'depars', 'areas', 'areaOther', 'nivel', 'avatars', 'api'));
    }
		protected $baseUrl = "http://172.16.100.10:8002/api/";
        protected $pesvUrl = "http://172.16.100.10:8004/api/";
        protected $ventasUrl = "http://172.16.100.10:8005/api/";
        protected $multimediaUrl = "http://172.16.100.10:8006/api/";
    public function apiholcim(Request $request, $any)
    {		
        $url = $this->baseUrl . $any;
 
        // Si hay query params, los agregamos
        if ($request->getQueryString()) {
            $url .= "?" . $request->getQueryString();
        }
 
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request->method());
 
        // Si viene con body (POST/PUT)
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getContent());
        }
 
        // Headers básicos (puedes añadir más si tu API lo requiere)
        $headers = [
            "Accept: application/json",
            "Content-Type: application/json",
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
 
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
 
        curl_close($ch);
 
        return response($result, $httpCode)
            ->header('Content-Type', 'application/json');
    }
    public function apipesv(Request $request, $any)
    {		
        $url = $this->pesvUrl . $any;
 
        // Si hay query params, los agregamos
        if ($request->getQueryString()) {
            $url .= "?" . $request->getQueryString();
        }
 
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request->method());
 
        // Si viene con body (POST/PUT)
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getContent());
        }
 
        // Headers básicos (puedes añadir más si tu API lo requiere)
        $headers = [
            "Accept: application/json",
            "Content-Type: application/json",
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
 
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
 
        curl_close($ch);
 
        return response($result, $httpCode)
            ->header('Content-Type', 'application/json');
    }
    public function apiventas(Request $request, $any)
    {		
        $url = $this->ventasUrl . $any;
 
        // Si hay query params, los agregamos
        if ($request->getQueryString()) {
            $url .= "?" . $request->getQueryString();
        }
 
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request->method());
 
        // Si viene con body (POST/PUT)
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getContent());
        }
 
        // Headers básicos (puedes añadir más si tu API lo requiere)
        $headers = [
            "Accept: application/json",
            "Content-Type: application/json",
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
 
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
 
        curl_close($ch);
 
        return response($result, $httpCode)
            ->header('Content-Type', 'application/json');
    }
    public function apimultimedia(Request $request, $any)
    {
        $url = $this->multimediaUrl . $any;
        if ($request->getQueryString()) {
            $url .= "?" . $request->getQueryString();
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request->method());

        // --- CRITICAL ADDITION 1: Capture Headers from Backend ---
        $responseHeaders = [];
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, function($curl, $header) use (&$responseHeaders) {
            $len = strlen($header);
            $header = explode(':', $header, 2);
            if (count($header) < 2) { 
                return $len; // Skip status line and empty headers
            }
            // Store header values in an array keyed by lowercase header name
            $responseHeaders[strtolower(trim($header[0]))][] = trim($header[1]);
            return $len;
        });
        // --- END CRITICAL ADDITION 1 ---

        // --- START: FILE UPLOAD PROXY LOGIC (Keep this as is) ---
        $requestHeaders = [];
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            if ($request->hasFile('file')) {
                $data = $request->all();
                $uploadedFile = $request->file('file');
                
                $data['file'] = new \CURLFile(
                    $uploadedFile->getPathname(),
                    $uploadedFile->getClientMimeType(),
                    $uploadedFile->getClientOriginalName()
                );

                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                
                // cURL handles multipart headers automatically. Only set Accept header.
                $requestHeaders = ["Accept: application/json"]; 

            } else {
                // Standard JSON POST/PUT
                curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getContent());
                $requestHeaders = [
                    "Accept: application/json",
                    "Content-Type: application/json",
                ];
            }
        } else {
            // GET, DELETE, etc.
            $requestHeaders = ["Accept: application/json"]; 
        }
        // --- END: FILE UPLOAD PROXY LOGIC ---

        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        // --- CRITICAL ADDITION 2: Forward Headers from Backend Response ---
        $response = response($result, $httpCode);

        // Pass through Content-Type (e.g., video/x-msvideo or application/json)
        if (isset($responseHeaders['content-type'])) {
            // Use 'end' to get the last instance of the header if duplicates exist
            $response->header('Content-Type', end($responseHeaders['content-type']));
        } else {
            // Fallback to application/json if Content-Type is missing (e.g., for JSON errors)
            $response->header('Content-Type', 'application/json'); 
        }
        
        // Pass through Content-Disposition (Crucial for the downloaded filename)
        if (isset($responseHeaders['content-disposition'])) {
            $response->header('Content-Disposition', end($responseHeaders['content-disposition']));
        }
        
        return $response;
        // --- END CRITICAL ADDITION 2 ---
    }
}
