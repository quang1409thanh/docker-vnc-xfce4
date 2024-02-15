# cách hoạt động
## giới thiệu
- chúng ta có 1 dự án viết bằng laravel về trang web nghe nhạc được lấy trên github, sử dụng cơ sở dữ liệu mysql.
- bây giờ chúng ta sẽ tiến hành build các container về ứng dụng này
- ứng dụng được chạy trên webserver là nginx và cơ sở dữ liệu là mysql. 2 cái này có thể lấy image trên mạng
- chúng ta sẽ build dự án ngoài các gói cần thiết thì có cài thêm `vncserver` và de là `xfce4` để có thể remote từ bên ngoài đến container này khi chạy.
- image build từ dự án được viết trong Dockerfile.
- và build các container trong docker-compose như sau.
    - app server tự build
    - web server:nginx
    - cơ sở dữ liệu: mysql
    - phpmyadmin: để xem cơ sở dữ liệu 1 cách trực quan trong container khi chạy
## bắt đầu
1. sau khi lệnh docker-compose up chạy thì nó sẽ build 4 container trên và nó sẽ chạy độc lập so với máy chủ của mình mà vẫn có đầy đủ chức năng của 1 ứng dụng client-server.
2. ở container app thì có ánh xạ cổng 5901 từ máy chủ vào cổng 5901 để có thể remote qua vnc.
3. chờ khoảng 180s hiện tại nó đang cài vnc server và xfce4
apt-get install dbus-x11
apt-get install x11-xserver-utils
touch /root/.Xresources
# docker-vnc-xfce4
