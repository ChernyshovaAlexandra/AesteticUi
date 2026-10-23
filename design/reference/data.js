/* Synthetic review data. Reloading the page restores the initial demonstration. */
window.AesteticReference = {
  customers: [
    { id: 'c-41', code: 'CL-0041', name: 'Нина Крылова', initials: 'НК', email: 'nina.krylova@example.com', phone: '+7 000 123-45-67', company: 'Студия «Север»', city: 'Санкт-Петербург', street: 'ул. Примерная, 12, офис 4', postal: '190000', country: 'Россия', note: 'Доставку удобнее согласовать во второй половине дня. Закрывающие документы отправлять по электронной почте.', notifications: true, since: '12 марта 2026', source: 'Добавлен вручную', updated: '21 октября 2026' },
    { id: 'c-42', code: 'CL-0042', name: 'Артём Волков', initials: 'АВ', email: 'artem.volkov@example.com', phone: '+7 000 234-56-78', company: '', city: 'Казань', street: 'ул. Образцовая, 7', postal: '420000', country: 'Россия', note: 'Позвонить перед доставкой.', notifications: true, since: '8 апреля 2026', source: 'Добавлен вручную', updated: '22 октября 2026' },
    { id: 'c-43', code: 'CL-0043', name: 'Полина Миронова', initials: 'ПМ', email: 'polina.mironova@example.com', phone: '+7 000 345-67-89', company: 'Мастерская «Форма»', city: 'Москва', street: 'ул. Тестовая, 18', postal: '101000', country: 'Россия', note: '', notifications: false, since: '3 сентября 2026', source: 'Добавлен вручную', updated: '20 октября 2026' }
  ],
  orders: [
    { id: '1048', customer: 'c-41', item: 'Настольная лампа Line', quantity: 2, amount: 24900, status: 'processing', date: '2026-10-23' },
    { id: '1047', customer: 'c-42', item: 'Органайзер Desk', quantity: 1, amount: 4800, status: 'new', date: '2026-10-23' },
    { id: '1046', customer: 'c-43', item: 'Стеллаж Frame', quantity: 1, amount: 38600, status: 'processing', date: '2026-10-22' },
    { id: '1045', customer: 'c-41', item: 'Подставка для книг Arc', quantity: 2, amount: 8400, status: 'completed', date: '2026-10-22' },
    { id: '1044', customer: 'c-42', item: 'Настольная лампа Line', quantity: 1, amount: 12450, status: 'completed', date: '2026-10-21' },
    { id: '1043', customer: 'c-43', item: 'Набор полок Shelf', quantity: 3, amount: 19200, status: 'processing', date: '2026-10-20' },
    { id: '1042', customer: 'c-41', item: 'Кресло Studio', quantity: 1, amount: 32900, status: 'completed', date: '2026-10-19' },
    { id: '1041', customer: 'c-42', item: 'Тумба Module', quantity: 1, amount: 21600, status: 'cancelled', date: '2026-10-18' }
  ],
  statuses: {
    new: { label: 'Новый', tone: 'blue' },
    processing: { label: 'В работе', tone: 'amber' },
    completed: { label: 'Выполнен', tone: 'green' },
    cancelled: { label: 'Отменён', tone: 'neutral' }
  }
};
