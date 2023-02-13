# prj-horaires
## REST API - cheat sheet
### API
#### Fetch data :
- ```/api/getGroups``` : return list of groups. Format : ```[{"idGroupe":"E13","bloc":3}, ...]```
- ```/api/getTeachers``` : return list of teachers. Format : ```[{"idProf": "SRV","nom": "Servais", "prenom": "Frédéric" }, ...]```
- ```/api/getLocals``` : return list of local. Format : ```[{"idSalle": "003","typeSalle": "E", "places": true, "placesExamen": false }, ...]```

#### Fetch Seances :
- ```/api/getGroups/<idGroupe>``` : return list of seance linked to passed group. Format : ```/api/getGroups/E13``` --> ```[{"idSeance": 25, "title": "PRJ", "start": "2011-10-05T14:48:00", "end": "2011-10-05T16:48:00"}, ...]```
- ```/api/getTeachers/<idProf>``` : return list of seance linked to passed teacher. Format : ```/api/getTeachers/SRV``` --> ```[{"idSeance": 25, "title": "PRJ", "start": "2011-10-05T14:48:00", "end": "2011-10-05T16:48:00"}, ...]```
- ```/api/getLocals/<idLocal>``` : return list of seance linked to passed local. Format : ```/api/getLocals/003``` --> ```[{"idSeance": 25, "title": "PRJ", "start": "2011-10-05T14:48:00", "end": "2011-10-05T16:48:00"}, ...]```

### Model
- To create the model, use by preference a shell command which will generate a model class
```
php artisan make:model <model's name>
```
- All functions that you'll create should be static in order to use them in the controller without instantiating.
- To prevent the SQL injections, you'll have to use prepared statements such as:
```
update: \DB::update("UPDATE Table SET column1=?, column2=? WHERE id=?",[$param1, $param2, $id]);
select: \DB::select("SELECT column1, column2 FROM Table WHERE id=?",[$id])
insert: \DB::insert("INSERT INTO Table VALUES(?,?)",[$param1, $param2]);
delete: \DB::delete("DELETE FROM Table WHERE id=?",[$id]);
```
- And then just simply return the query result only if it should return the data.
- Example of a function:
```php
public static function getData($id){
    return \DB::select("SELECT * FROM Tables WHERE id=?",[$id]);
}
```

### Controller
- To create the controller, use by preference a shell command which will generate a controller class
```
php artisan make:controller <controller's name>
```
- Firstly import the model that the controller will be using, example:
```php
use App\Models\<model>;
```
- Every controller function is supposed to return the status of the model call
```php
If no data returned and no exception: response()->json(true, <positive status>);
If no data returned and exception: return response()->json(false, 500);
If data returned and no exception (response contains the result of the query executed by the model): 
http_response_code(<positive status>);
return response()->json($response);
If data returned and exception: return response()->json(false, 500);
```
- Also it should treat eventually the exceptions
- The returned data have to be in the JSON format, so encode the result 
- For the POST request, all the data is transmitted throught Request $request, in order to retrieve the data you'll have to import Request 
```php
use Illuminate\Http\Request;
```
- To retrieve the data just use:
```php
$request->post("<var's name>")
```
- Example of a simple function: 
```php
public static function getSomeData($var){
    $response=null;
    try {
        $response = Model::getSomeData($var);
    } catch (Exception $ex) {
        return response()->json(false, 500);
    }
    http_response_code(200);
    return response()->json($response);
}
``` 
### Routes
- Two different types of REST Api services:
- GET (Only to retrieve the data)
    - Data transmitted through the URL:
```php
Route::get('/getSomeData/{variable}',[Model::class,'<name of the method>']);
```
- POST (Only to send the data)
    - Data passed through the body of the request
```php
Route::post('/updateSomeData',[Model::class,'<name of the method>']);
```

### Deployment
The deployment branch has a custom .env file for deployments (Heroku). Please don't edit and commit the .env file according your local configuration. 
#### Login heroku :
- user : ```idalie@passedil.com```
- password : ```He2besiprjE13&```
- app_name : ```he2b-esi--horaires```
- old url : https://he2b-esi--horaires.herokuapp.com/
- new url : https://prj-horaires.azurewebsites.net/
#### To do once :
1. Login to Heroku.
```bash
heroku login
```

2. Add the heroku remote branch. The <app_name> is your project name.
```bash
heroku git:remote -a <app_name>
```

#### To push an update/first deployment.
Deployment is automatically done with pipeline if unit tests succeed.
### Google Authentification 
- To use the google authentification, you'll have to create a google OAuth crediential for the authentification. You can find how to do it on this website 
```https://www.webdew.com/blog/login-with-google-in-laravel```, just follow the step 3.

otherwise add this line to your .env file 
```
GOOGLE_CLIENT_ID=317921197674-0t22ukcqifahmhchkra84j3lkr7n0h19.apps.googleusercontent.com 
GOOGLE_CLIENT_SECRET=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
GOOGLE_REDIRECT_URI="http://localhost:8000/callback" 
```
> **Warning** With these credentials, you'll have to use your HE2B account.
