$('#loginForm')
        .bootstrapValidator({
            message: 'This value is not valid',
            //live: 'submitted',
//                feedbackIcons: {
//                    valid: 'fab fa-adn',
//                    invalid: 'fab fa-adn',
//                    validating: 'fab fa-address-car'
//                },
            fields: {
                login: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Введите логин'
                        },
//                        stringLength: {
//                            min: 3,
//                            max: 10,
//                            message: 'от 3 до 10 символов'
//                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9_]+$/,
                            message: 'буквы англ.алфавита, цифры, нижнее подчеркивание '
                        }
                    }
                },
                password: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Введите пароль'
                        },
//                        stringLength: {
//                            min: 3,
//                            max: 7,
//                            message: 'от 3 до 7 символов'
//                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9]+$/,
                            message: 'буквы англ.алфавита, цифры '
                        }
                    }
                }
            }

        });