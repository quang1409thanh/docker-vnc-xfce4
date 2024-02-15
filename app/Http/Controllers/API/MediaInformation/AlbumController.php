<?php

namespace App\Http\Controllers\API\MediaInformation;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Song;
use App\Services\MediaInformationService;

class AlbumController extends Controller
{
    public function __construct(private MediaInformationService $mediaInformationService)
    {
    }

    public function show($id)
    {
        try {
            // Tìm album theo id
            $album = Album::findOrFail($id);

            // Load thông tin album và danh sách bài hát cùng với thông tin nghệ sĩ
            $albumWithSongsAndArtist = $album->load(['songs', 'artist']);

            // Loại bỏ trường artist_id nếu có thông tin về artist
            if ($albumWithSongsAndArtist->artist) {
                unset($albumWithSongsAndArtist->artist_id);
            }

            // Trả về dữ liệu
            return response()->json($albumWithSongsAndArtist);
        } catch (\Exception $e) {
            // Xử lý ngoại lệ nếu không tìm thấy album
            return response()->json([
                'success' => false,
                'message' => 'Album not found',
            ], 404);
        }
    }
}
