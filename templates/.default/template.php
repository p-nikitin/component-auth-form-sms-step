<?php
/**
 * @var $arResult array
 */

use Bitrix\Main\UI\Extension;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
Extension::load(['ui.vue']);
?>

<div id="auth-form" class="popup popup-small">
    <div class="modal__head">
        <div class="modal__title">Вход в личный кабинет</div>
    </div>
    <div class="modal__body">
        <form class="modal__form" @submit="submitHandler" ref="form">
            <label class="modal__label">
                <input type="text" class="modal__input input phone-input" v-model="phone"
                       :disabled="step === 'code'" placeholder="7 (ххх)  ххх - хх-хх" name="phone"
                       data-inputmask data-input-required="phone">
            </label>
            <label class="modal__label" v-if="step == 'code'">
                <input type="text" class="modal__input input" v-model="code"
                       placeholder="Введите код из СМС*" name="code" minlength="4" maxlength="4"
                       data-inputmask data-input-required="code">
            </label>
            <div class="modal__button">
                <button type="submit" class="modal__submit btn btn--brown--dark">
                    <span v-if="step === 'phone'">Получить код</span>
                    <span v-if="step === 'code'">Войти</span>
                </button>
                <div class="modal__privacy" v-if="step === 'phone'">
                    Нажимая кнопку вы соглашаетесь с
                    <a href="/privacy-policy/">политикой обработки персональных данных</a>
                </div>
                <div class="modal__code" v-if="step === 'code'">
                    Не пришел код?
                    <span v-if="timeLeft > 0">Повторить отправку можно через <span>{{ timeLeft }}</span> сек</span>
                    <button type="button" class="resend-code" @click="resendCode" v-if="timeLeft <= 0">
                        Отправить код
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    new AuthForm('auth-form');
</script>
