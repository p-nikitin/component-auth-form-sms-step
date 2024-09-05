class AuthForm {
    constructor(rootId) {
        this.rootId = rootId;
        this.root = document.getElementById(this.rootId);
        if (this.root) {
            this.init()
        }
    }

    init() {
        this.templateEngine = BX.Vue.create({
            el: '#' + this.rootId,
            name: 'AuthForm',
            data: {
                phone: '',
                code: '',
                step: 'phone',
                timeLeft: 60,
                interval: null
            },
            mounted() {
                App.InputMask.init();
            },
            methods: {
                submitHandler(evt) {
                    evt.preventDefault();
                    const form = evt.target;
                    if (App.Forms.FormValidation.validate($(form))) {
                        BX.showWait();
                        if (this.step === 'phone') {
                            this.sendCode(form);
                        } else if (this.step === 'code') {
                            this.checkCode(form);
                        }
                    }
                },
                resendCode() {
                    this.code = '';
                    this.sendCode(this.$refs.form);
                },
                sendCode(form) {
                    const self = this;
                    BX.ajax.runAction('izifir:core.api.user.sendCode', {
                        data: {phoneNumber: this.phone}
                    }).then(resp => {
                        if (resp.data.status === 'success') {
                            self.step = 'code';
                            self.initTimer();
                        } else {
                            App.Forms.FormValidation.invalid($(form.phone), resp.data.message);
                        }
                        BX.closeWait();
                    });
                },
                checkCode(form) {
                    const self = this;
                    BX.ajax.runAction('izifir:core.api.user.checkCode', {
                        data: {
                            phoneNumber: self.phone,
                            code: self.code
                        }
                    }).then(resp => {
                        if (resp.data.status === 'success') {
                            location.reload();
                        } else {
                            App.Forms.FormValidation.invalid($(form.code), resp.data.message);
                        }
                        BX.closeWait();
                    });
                },
                initTimer() {
                    const self = this;
                    clearInterval(self.interval);
                    self.timeLeft = 60;
                    self.interval = setInterval(() => {
                        self.timeLeft--;
                        if (self.timeLeft <= 0) {
                            clearInterval(self.interval);
                        }
                    }, 1000);
                }
            }
        });
    }
}
