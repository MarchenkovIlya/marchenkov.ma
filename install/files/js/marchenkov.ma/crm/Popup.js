(function () {
    BX.namespace('marchenkov.ma.crm');

    BX.marchenkov.ma.crm.Popup = class Popup {
        static instance;
        chatsSelector = {};
        user = {};

        static create() {
            if (!Popup.instance) {
                Popup.instance = new Popup();
            }

            return Popup.instance;
        }

        bind() {
            return new Promise(resolve => {
                this.popup = BX.PopupWindowManager.create('send_crm_entity', null, {
                    titleBar: 'Выберите пользователей, с которыми хотите поделиться.',
                    content: 'test',
                    width: 580,
                    closeIcon: {
                        opacity: 1
                    },
                });
                this.showPopup().then(resolve);
            })
        }

        showPopup() {
            return new Promise(resolve => {
                    BX.ajax.runComponentAction(
                        'marchenkov.ma:crm.popup',
                        'viewTemplateForSending',
                        {
                            mode: 'class',
                            signedParameters: {},
                            data: {
                                template: '.default'
                            }
                        }
                    ).then(r => {
                            this.popup.setButtons([
                                new BX.PopupWindowButton({
                                    text: 'Отправить',
                                    className: "popup-window-button-accept",
                                    events: {
                                        click: () => {
                                            const inputUsers = document.querySelectorAll('.selected-user');
                                            let userIds = [];
                                            inputUsers.forEach((element) => {
                                                userIds.push(element.value);
                                            })
                                            const message = document.getElementById('message');
                                            BX.ajax.runAction(
                                                'marchenkov:ma.chat.Service.sendMessage', {
                                                    data: {
                                                        userIds: userIds,
                                                        message: message.value,
                                                        pathname: location.pathname
                                                    }
                                                }
                                            ).then(response => console.log(response)).catch(function (reason) {
                                                console.log(reason);
                                            });
                                            this.popup.close();
                                        }
                                    }
                                }),
                                new BX.PopupWindowButton({
                                    text: 'Отменить',
                                    className: "webform-button-link-cancel",
                                    events: {
                                        click: () => {
                                            this.popup.close();
                                        }
                                    }
                                })
                            ]);
                            this.popup.setContent(BX.Tag.render`${r.data.html}`);
                            this.popup.show();
                            this.createChatsSelector();
                        }
                    ).catch(function (reason) {
                        console.log(reason);
                    })
                }
            )
        }

        createChatsSelector() {
            this.chatsSelector.selectorContainer = document.querySelector('#chats-selector-container');
            this.container = document.querySelector('#container');
            this.chatsSelector.input = document.querySelector('#chats-selector');
            this.user.tagSelector = new BX.UI.EntitySelector.TagSelector({
                id: `chats-selector`,
                enableSearch: true,
                multiple: true,
                dialogOptions: {
                    multiple: true,
                    entities: [
                        {
                            id: 'user', // пользователи
                        },
                    ],
                },
                events: {
                    onTagAdd: (e) => {
                        const {tag} = e.getData();
                        const inputElement = document.createElement('input');
                        inputElement.setAttribute('class', 'selected-user');
                        inputElement.setAttribute('type', 'hidden');
                        inputElement.setAttribute('value', tag.id);
                        this.container.appendChild(inputElement);
                    }
                }
            });

            this.user.tagSelector.renderTo(this.chatsSelector.selectorContainer);
        }
    }
})();