<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Song;
use App\Repositories\InteractionRepository;
use App\Repositories\PlaylistRepository;
use App\Repositories\SettingRepository;
use App\Repositories\UserRepository;
use App\Services\ApplicationInformationService;
use App\Services\ITunesService;
use App\Services\LastfmService;
use App\Services\MediaCacheService;
use App\Services\YouTubeService;
use Illuminate\Contracts\Auth\Authenticatable;

class DataController extends Controller
{
    private const RECENTLY_PLAYED_EXCERPT_COUNT = 7;

    /** @param User $currentUser */
    public function __construct(
        private MediaCacheService $mediaCacheService,
        private SettingRepository $settingRepository,
        private PlaylistRepository $playlistRepository,
        private InteractionRepository $interactionRepository,
        private UserRepository $userRepository,
        private ApplicationInformationService $applicationInformationService,
        private ?Authenticatable $currentUser
    ) {
    }

    public function index()
    {
        return response()->json($this->mediaCacheService->get() + [
            'settings' =>$this->settingRepository->getAllAsKeyValueArray(),
            'playlists' => $this->playlistRepository->getAllByCurrentUser(),
            'interactions' => $this->interactionRepository->getAllByCurrentUser(),
            // 'recentlyPlayed' => $this->interactionRepository->getRecentlyPlayed(
            //     $this->currentUser,
            //     self::RECENTLY_PLAYED_EXCERPT_COUNT
            // ),
            'users' =>$this->userRepository->getAll(),
            'currentUser' => $this->currentUser,
        ]);
    }
    public function responseDATA() {
        // Tạo một mảng chứa dữ liệu cố định
        $data = [
            'message' => 'Du lieu tinh tu API',
            'songs' => [
                ['id' => 1, 'title' => 'Bai Hat 1'],
                ['id' => 2, 'title' => 'Bai Hat 2'],
                // Thêm các bài hát khác tại đây
            ],
            'users' => [
                ['id' => 1, 'name' => 'Nguoi dung 1'],
                ['id' => 2, 'name' => 'Nguoi dung 2'],
                // Thêm các người dùng khác tại đây
            ],
        ];

        // Chuyển mảng dữ liệu thành JSON và đảm bảo mã hóa dấu ký tự đặc biệt đúng cách
        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);

        // Trả về JSON response
        return response()->json($jsonData)
            ->header('Access-Control-Allow-Origin', 'http://127.0.0.1:5500');
    }
    public function getAllSongs()
    {
        $songs = Song::all(); // Truy vấn tất cả các bài hát
    
        return response()->json([
            'status' => 'success',
            'message' => 'All songs retrieved successfully.',
            'data' => $songs,
        ]);
    }
    
}
