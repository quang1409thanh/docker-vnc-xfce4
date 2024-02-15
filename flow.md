đầu tiên là lấy dữ liệu từ home.
getHome
getPlayList
sau lấy dữ liệu parse ra các thành phần tương ứng như PlayList có thuộc tính là các track
sau khi parse được và lấy các track có dữ liệu rồi sẽ xử lý dữ liệu trên đó.
đầu tiên khi click vào 1 track, 
- setNowPlaying(track);
- sau đó chuyển đến:findNavController().navigateSafe(R.id.action_global_nowPlayingFragment, args)
- và đồng thời sẽ chuyển đến 1 đống dữ liệu gói trong đó bao gồm như
                    
            val args = Bundle()
            args.putString("type", Config.PLAYLIST_CLICK)
            args.putString("videoId", viewModel.playlistBrowse.value?.data!!.tracks[position].videoId)
            args.putString("from", "Playlist \"${viewModel.playlistBrowse.value?.data!!.title}\"")
            args.putString("playlistId", viewModel.playlistBrowse.value?.data?.id?.replaceFirst("VL", ""))
            args.putInt("index", position)

- sau đó nó sẽ xử lý dữ liệu ở fragment action_global_nowPlayingFragment như

        lấy ra dữ liệu xem thuộc dạng nào
        type = arguments?.getString("type")
        videoId = arguments?.getString("videoId")
        from = arguments?.getString("from") ?: viewModel.from.value
        index = arguments?.getInt("index")
        downloaded = arguments?.getInt("downloaded")
        playlistId = arguments?.getString("playlistId")

- sau khi xác định được loại nào thì tiếp tục thực hiện

ví dụ từ PlayList thì

    PLAYLIST_CLICK -> {
            if (playlistId != null) {
                viewModel.playlistId.value = playlistId
            }
        Log.i("Now Playing Fragment", "Playlist Click")
        binding.ivArt.setImageResource(0)
            binding.loadingArt.visibility = View.VISIBLE
            viewModel.gradientDrawable.postValue(null)
            viewModel.lyricsBackground.postValue(null)
            binding.tvSongTitle.visibility = View.GONE
            binding.tvSongArtist.visibility = View.GONE
            Queue.getNowPlaying()?.let {
                viewModel.simpleMediaServiceHandler?.reset()
                viewModel.resetRelated()
                Log.d("check index", index.toString())
                viewModel.loadMediaItemFromTrack(it, PLAYLIST_CLICK, index)
                viewModel.videoId.postValue(it.videoId)
                viewModel.from.postValue(from)
                updateUIfromQueueNowPlaying()
        }
    }

- lấy track từ getNowPlaying và chuyển sang media để phát 
 
bời hàm     `loadMediaItemFromTrack`

- chèn vào db            
    
        mainRepository.insertSong(track.toSongEntity())
        sau đó lấy bài hát đó ra và cập nhật các giá trị như like.
        
- su đấy làm 1 đống:
- quan trọng nhất làm hàm getStream. hơi khoai
trước đó sẽ xử lý được hết dữ liệu rồi, còn bây giờ là stream thôi
/home/thanhyk14/Desktop/MySound/app/src/main/java/uet/app/mysound/ui/fragment/player/NowPlayingFragment.kt

GRANT ALL ON laravel_web.* TO 'root'@'%' IDENTIFIED BY '140903';FLUSH PRIVILEGES;