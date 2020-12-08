<!-- Modal -->
<div class="modal fade" id="modalAgree" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">利用規約</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_agree" class="form_agree" action="">
                {{ csrf_field() }}
                <div class="modal-body pb-5">
                    <div>
                        <input type="email" id="email" class="form-control" name="email" value="" placeholder="メールアドレス"
                               style="width: 500px">
                        <span>※パスワード再発行の際に利用いたします</span>
                    </div>
                    <div class="mt-3 overflow-auto"
                         style="height: 300px; background-color: #fefefe; border: solid 1px; border-color: #bfc4c4">
                         {{-- 利用規約の内容は以下のdiv要素内に入る --}}
                        <div id="privacy_body" style="font-size: 0.7em; padding: 10px"></div>
                    </div>
                    <hr>
                    <div class="modal_agree">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="check_agree" name="check_agree">
                            <label class="custom-control-label cursor-pointer" for="check_agree">利用規約に同意する</label>
                        </div>
                        <div class="text-center">
                            <button id="button_agree" type="button" class="btn button__main" disabled>利用規約に同意して登録する</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
