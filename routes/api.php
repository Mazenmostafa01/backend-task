<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Api\ForgetPasswordController;


Route::post('/login', [UserController::class, 'store']);
Route::post('/refresh', [UserController::class, 'refresh']);
//password reset
Route::post('/forgot-password', [ForgetPasswordController::class, 'sendResetLinkMail']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset');
//categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{categories}', [CategoryController::class, 'show']);
//product
Route::get('/products', [ProductController::class, 'index']);

Route::middleware(['auth', 'role:admin'])->group(function()
{
    Route::post('/import', [CategoryController::class , 'import']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::post('/categories/{categories}', [CategoryController::class, 'update']);
    Route::delete('/categories/{categories}', [CategoryController::class, 'destroy']);
});

Route::middleware(['auth', 'role:admin|customer'])->group(function()
{
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::post('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
});


/* 
test@gmail.com token eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiY2E1YWYzZjZhMGRiNGIzNTBmZDRlNTFiMTc3YTZlMzEwNzNlNDU2NzI3Y2MwMjU5YjJmMTZmODI5ZDM3YTg1OTg2MzI2OTFjMTZlYWE0ODkiLCJpYXQiOjE3NDI0MTA5MjguMTE4Njk2LCJuYmYiOjE3NDI0MTA5MjguMTE4NzAzLCJleHAiOjE3NzM5NDY5MjcuOTY3NjE3LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.okJPyamuR21DNOHxzRmUlM4cITX2ib1zaOnJmG_0Yu-_gB3rv_BUcezii6jLttZwg7F9lAK5KqZdNfON6KOc52nfE8TwKk9CP6slOC9SUeVRNafjgJPzaI2riWOTWRh2X1LJnW_2iyd8uL5qC9LKHSX6Kc6ZJOEir46CcZtY_2K8_6kqF0uVtGx_z7zyptQzrsgUDSW1H6QL35FA_r80Lfc8ixBKYyRnEWsxLZK3askq7Am0IfvS3EmpPTk96mlmtQDfs8oMJFTEgd05c4ZQx0vFeFHOeAye54ojrnd9Qry8SQ48OQHUfb3QXKwR4uZb2OC1cJ7WgM--EaVCdg9TNhUxvR16clhivvG6kK4Unust3kQ2HVi2kE62ffJFveUsiB2KTfagEHbDbteqfGMiI28-JrINK9S4tmTefKNBI16d3kEF2gjauH2nV1xFB_td-zJKNE9rhWlBzR5uSS2YVmSXb6_5LYXgxP8XQDEidqeH8yigFiR22gJAIaaq7oVwlVpJfZy20AJsknzBaA7jmsnFNjXPKRG5FvKDgpGskg2qhN5olXKCRFO_ZQBreRDR3HTaZOk1aLDNERGUHvd3ZP12y2iYIgJmRX5_lMQlK5DCzxFIb7eh6ODVvL1vNlPXFa_PhWjIBYf220fLNtRAcLWcNQJSQLy1U50M7c0iGHI
customer@gmai.com token eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiYTg4MTE1NTRlNTE4MTJlODBiNDY5ZjAzZDMwOWQ5MjlkYWVjNTE5NGY1ODdmODI2MmNlZWI0NWMzNmNmYzQ0MjQxMWQ4MTk4YmY3NDM4YjAiLCJpYXQiOjE3NDI0NjgzMjQuODEyNzgyLCJuYmYiOjE3NDI0NjgzMjQuODEyNzkzLCJleHAiOjE3NzQwMDQzMjQuNjUxMDAzLCJzdWIiOiIzIiwic2NvcGVzIjpbXX0.AVxCobPavZgovCuQn9Oh6Ik87guB21oE8Tx__BkTcusQU6iOvDw0jMyWceJpUnjjdGKZZ2Lwz2Z1qGeNbMcQ8N5VPlasTMfE7kFln5VojtsyvsbmnnXwgzKTRBCES1v5a14esKwlQpyPWEtSqfMzZiZh4T7h749aogZhVnhbzjsTzhNsyL9clhH2teGQG6WooxEdik2B__-Oj5sbEUZhKuHjOmQ3FfvVk2ykaPsJbYER_xl3rp2hGOUytVdrFgXBPtXHgpl8ysZW-BTxZlkTnOEoTm725wzGpJBpsFYjU5zCf56_4-pT4bahZSUv-wZ05cc48cn3Xyh3eV8pvW4s2xi1ZDbtmy2uFHyYhmIYfOYqbk6X6WBhYasvUG_9u4NltxZGNjAJftzLsqa5J020nsMZJHGXvd17TJ0Agqk_0mN33NpOGBOoSwmrYJo2X8Nv0Rtm6aPMVGJEZZFeOez2KY7EygsWpYlssLvp1P0qnTtBOHZHUJX3AMZsCMPpPmiqjRBu6apCloesPCbrFlt0VD6iK0hMH3AdpveVelGK-pgJDyocPll-XhInAKCLQtFN0o78zhcff_7GOIfXBhWYyU22krnUlv8OmDbEHAUavTe0YjG28dsfGLoF6rpt8gAUmog-_990TU3mZLa9cmehDxSnm0r-TWnnRKv3ysOfVak
*/