<body>
    <div
        style="max-width: 700px; border-radius: 10px; margin:auto; background: linear-gradient(0deg, rgba(135, 206, 235, 0.5), rgba(255, 0, 150, 0.3)), url('https://images.unsplash.com/photo-1606768666853-403c90a981ad?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1742&q=80'); background-position: center; background-repeat: no-repeat; background-size: cover;">
        <div class="container email-container "
            style="box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;  border-radius: 5px; font-family: 'Montserrat', sans-serif; margin: 0 auto; background: rgb(255, 255, 255, 0.92); padding: 20px 10px">
            <div style="display: flex;">
                <div
                    style="border-bottom:1px solid rgb(217, 217, 217) ; text-align: center; margin: 0 auto; padding: 1em 0;">
                    <img src="https://i.pinimg.com/564x/e8/df/01/e8df017b8459c3fdc951da06ce0be115.jpg"
                        alt=""style="max-width: 300px;">
                </div>
            </div>
            <h4 class="text-center mt-3 " style="font-weight: 700;text-align: center; margin-top: 1em;">Kính gửi: Quý
                khách {{ $orderData['name'] }}</h4>
            <h5 class="text-center" style="text-align: center;">Đơn hàng của bạn đã được thanh toán thành công</h5>
            <div class="mail-contents mt-4" style="padding: 0 10px;margin-top: 1.5em;">
                <h4 class="text-center " style=" color: #FE6531;font-weight: 800;text-align: center;">Chi tiết hoá đơn
                </h4>
                <div class="row mt-4" style="display: flex;margin-top: 1em;">
                    <div class="col text-start" style="font-weight: 700;text-align: left; width: 40%;">Mã hoá đơn</div>
                    <div class="col text-end" style="text-align: right; width: 60%;">{{ $orderData['code_bill'] }}</div>
                </div>
                <div class="row mt-2" style="display: flex;margin-top: 1em;">
                    <div class="col text-start" style="font-weight: 700;text-align: left; width: 40%;">Mã vé</div>
                    <div class="col text-end" style="text-align: right; width: 60%;">
                        <?php
                        foreach ($orderData['code_tickets'] as $value) {
                            echo $value . ', ';
                        }
                        ?>
                    </div>
                </div>
                <div class="row mt-2" style="display: flex;margin-top: 1em;">
                    <div class="col text-start" style="font-weight: 700;text-align: left; width: 40%;">Khách hàng</div>
                    <div class="col text-end" style="text-align: right; width: 60%;">{{ $orderData['name'] }}</div>
                </div>
                <div class="row mt-2" style="display: flex;margin-top: 1em;">
                    <div class="col text-start" style="font-weight: 700;text-align: left; width: 40%;">Số điện thoại
                    </div>
                    <div class="col text-end" style="text-align: right; width: 60%;">{{ $orderData['phone'] }}</div>
                </div>
                <div class="row mt-2" style="display: flex;margin-top: 1em;">
                    <div class="col text-start" style="font-weight: 700;text-align: left; width: 40%;">Tuyến xe</div>
                    <div class="col text-end" style="text-align: right; width: 60%;">
                        {{ $orderData['trip']['start']['name'] }} => {{ $orderData['trip']['end']['name'] }}</div>
                </div>
                <div class="row mt-2" style="display: flex;margin-top: 1em;">
                    <div class="col text-start" style="font-weight: 700;text-align: left; width: 40%;">Số lượng vé</div>
                    <div class="col text-end" style="text-align: right; width: 60%;">{{ $orderData['quantity'] }}</div>
                </div>
                <div class="row mt-2" style="display: flex;margin-top: 1em;">
                    <div class="col text-start" style="font-weight: 700;text-align: left; width: 40%;">Ghế</div>
                    <div class="col text-end" style="text-align: right; width: 60%;">
                        <?php
                        foreach ($orderData['seats'] as $value) {
                            echo $value . ', ';
                        }
                        ?>
                    </div>
                </div>
                <div class="row mt-2" style="display: flex;margin-top: 1em;">
                    <div class="col text-start" style="font-weight: 700;text-align: left; width: 40%;">Ngày khởi hành
                    </div>
                    <div class="col text-end" style="text-align: right; width: 60%;">{{ $orderData['trip']["departure_time"] }}</div>
                </div>
                <div class="row mt-2" style="display: flex;margin-top: 1em;">
                    <div class="col text-start" style="font-weight: 700;text-align: left; width: 40%;">Giờ khởi hành</div>
                    <div class="col text-end" style="text-align: right; width: 60%;"> 00:00</div>
                </div>
                <div class="row mt-2" style="display: flex;margin-top: 1em;">
                    <div class="col text-start" style="font-weight: 700;text-align: left; width: 40%;">Điểm đón</div>
                    <div class="col text-end"style="text-align: right; width: 60%;">Cổng chính (Quốc Lộ 22, Ấp Đông Lân,
                        Bà Điểm, Hóc Môn, TP Hồ Chí Minh)</div>
                </div>

                <div class="row mt-2" style="display: flex;margin-top: 1em;">
                    <div class="col text-start" style="font-weight: 700;text-align: left; width: 40%;">Phương thức thanh
                        toán</div>
                    <div class="col text-end" style="text-align: right; width: 60%;">{{ $orderData['payment_method'] }}</div>
                </div>
                <div class="row mt-3"
                    style="background-color: rgb(218, 218, 218) ;padding: 10px 5px; color: #FE6531; font-weight: 800;margin-top: 1.5em;display: flex;">
                    <div class="col text-start " style="font-weight: 700;text-align: left; width: 40%;">Tổng thanh toán
                    </div>
                    <div class="col text-end" style="text-align: right; width: 60%;"><?php echo number_format($orderData['total_price']) ?> VND</div>
                </div>
                <p class="text-center py-3" style="text-align: center; padding: 15px 0;">Mang mã vé đến văn phòng để đổi
                    vé lên xe trước giời xuất bến ít nhất 60 phút</p>
            </div>
        </div>
    </div>
</body>
