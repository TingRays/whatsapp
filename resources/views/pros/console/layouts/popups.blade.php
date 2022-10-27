{{--修改密码弹窗：Start--}}
<div class="modal fade" id="kt-edit_admin_password_modal" data-query-url="{{ route('pros.console.admins.change.password') }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2>修改管理员密码</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </div>
            </div>
            <div class="modal-body py-10 px-lg-17">
                <div class="notice d-flex bg-light-success rounded border-succ border border-dashed mb-9 p-6">
                    <span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"></rect>
                            <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="black"></rect>
                            <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="black"></rect>
                        </svg>
                    </span>
                    <div class="d-flex flex-stack flex-grow-1">
                        <div class="fw-bold">
                            <h4 class="text-gray-900 fw-bolder">操作提示</h4>
                            <div class="fs-6 text-gray-700">管理员密码修改成功后将在下次登录时生效，如需立即验证可点击 <a href="">前往登录页面</a></div>
                        </div>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-12 my-3 form_item">
                        <label class="required fs-5 fw-bold mb-2">新密码</label>
                        <input type="password" class="form-control form-control-solid" autocomplete="off" placeholder="请输入账户新密码" id="kt-edit_admin_password_modal_new_password">
                    </div>
                    <div class="col-md-12 my-3 form_item">
                        <label class="required fs-5 fw-bold mb-2">确认新密码</label>
                        <input type="password" class="form-control form-control-solid" autocomplete="off" placeholder="请在此输入账户新密码" id="kt-edit_admin_password_modal_new_password_confirmed">
                    </div>
                </div>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" data-bs-dismiss="modal" class="btn btn-light me-3">取消</button>
                <button type="button" id="kt-edit_admin_password_modal_confirm_button" class="btn btn-primary">确认修改</button>
            </div>
        </div>
    </div>
</div>
{{--修改密码弹窗:End--}}
