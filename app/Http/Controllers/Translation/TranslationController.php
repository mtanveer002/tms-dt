<?php

namespace App\Http\Controllers\Translation;

use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\TranslationService;
use App\Http\Requests\StoreTranslationRequest;
use App\Http\Requests\UpdateTranslationRequest;

class TranslationController extends Controller
{

    protected $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }
    
    /**
     * @OA\SecurityScheme(
     *     securityScheme="sanctum",
     *     type="http",
     *     scheme="bearer"
     * )
     */

    /**
     * Get a list of translations.
     *
     * @OA\Get(
     *     path="/api/translations",
     *     tags={"translations"},
     *     summary="Get all translations",
     *     security={{"sanctum":{}}},
     *     description="Returns a list of translations",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json($this->translationService->getAllTranslations());
    }

    /**
     * @OA\Post(
     *     path="/api/translations",
     *     summary="Create a new translation",
     *     security={{"sanctum":{}}},
     *     tags={"translations"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"locale", "key", "content", "tags"},
     *             @OA\Property(property="locale", type="string", example="eng"),
     *             @OA\Property(property="key", type="string", example="welcome_message"),
     *             @OA\Property(property="content", type="string", example="{\`en\`:\`Welcome\`,\`es\`:\`Bienvenido\`}"),
     *             @OA\Property(
     *                 property="tags",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 example={"greeating", "welcome"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Translation created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="locale", type="string", example="eng"),
     *             @OA\Property(property="key", type="string", example="welcome_message"),
     *             @OA\Property(property="content", type="string", example="{\`en\`:\`Welcome\`,\`es\`:\`Bienvenido\`}"),
     *             @OA\Property(
     *                 property="tags",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 example={"greeating", "welcome"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="An error occurred")
     *         )
     *     )
     * )
     */
    public function store(StoreTranslationRequest $request): JsonResponse
    {
        try {
            $translation = $this->translationService->createTranslation($request->only(['locale', 'key', 'content', 'tags']));
            return response()->json($translation, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/translations/{id}",
     *     summary="Get a specific translation",
     *     security={{"sanctum":{}}},
     *     tags={"translations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Translation ID"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Translation not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Translation not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="An error occurred")
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        return response()->json($this->translationService->getTranslationById($id));
    }

   
    /**
     * @OA\Put(
     *     path="/api/translations/{id}",
     *     summary="Update an existing translation",
     *     security={{"sanctum":{}}},
     *     tags={"translations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Translation ID"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"locale", "key", "content", "tags"},
     *             @OA\Property(property="locale", type="string", example="eng"),
     *             @OA\Property(property="key", type="string", example="welcome_message"),
     *             @OA\Property(property="content", type="string", example="{\`en\`:\`Welcome\`,\`es\`:\`Bienvenido\`}"),
     *             @OA\Property(
     *                 property="tags",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 example={"greeating", "welcome"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Translation updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="locale", type="string", example="eng"),
     *             @OA\Property(property="key", type="string", example="welcome_message"),
     *             @OA\Property(property="content", type="string", example="{\`en\`:\`Welcome\`,\`es\`:\`Bienvenido\`}"),
     *             @OA\Property(
     *                 property="tags",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 example={"greeating", "welcome"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Translation not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Translation not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="An error occurred")
     *         )
     *     )
     * )
     */
    public function update(UpdateTranslationRequest $request, Translation $translation): JsonResponse
    {
        try {
            $data = $request->only(['locale', 'content', 'tags']);
            $updatedTranslation = $this->translationService->updateTranslation($translation, $data);
            return response()->json($updatedTranslation);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/translations/{id}",
     *     summary="Delete a translation",
     *     security={{"sanctum":{}}},
     *     tags={"translations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Translation ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Translation deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Translation deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Translation not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Translation not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="An error occurred")
     *         )
     *     )
     * )
     */
    public function destroy(Translation $translation): JsonResponse
    {
        try {
            $this->translationService->deleteTranslation($translation);
            return response()->json(['message' => 'Translation deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Search for translations by query.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('query');
        $translations = $this->translationService->searchTranslations($query);
        return response()->json($translations);
    }

    
    /**
     * Get all translations that have a given tag name.
     *
     * @param string $tagName
     * @return JsonResponse
     */
    public function getTranslationsByTag(string $tagName): JsonResponse
    {
        $translations = $this->translationService->getTranslationsByTag($tagName);
        return response()->json($translations);
    }

    
    /**
     * Assign tags to a given translation.
     *
     * @param Request $request
     * @param Translation $translation
     * @return JsonResponse
     */
    public function assignTags(Request $request, Translation $translation): JsonResponse
    {
        $tags = $request->get('tags', []);
        $translation = $this->translationService->assignTagsToTranslation($translation, $tags);
        return response()->json($translation);
    }

    
    /**
     * Export all translations as a JSON response.
     *
     * @return JsonResponse
     */
    public function export(): JsonResponse
    {
        $translations = $this->translationService->exportTranslations();
        return response()->json($translations);
    }
}
