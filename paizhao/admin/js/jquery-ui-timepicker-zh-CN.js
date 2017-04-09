/* ���� Datepicker �� Timepicker��Written by ��־�� */
(function ($) {
    // ���� Datepicker
    $.datepicker.regional['zh-CN'] =
    {
        clearText: '���', clearStatus: '�����ѡ����',
        closeText: '�ر�', closeStatus: '���ı䵱ǰѡ��',
        prevText: '&lt;����', prevStatus: '��ʾ����',
        nextText: '����&gt;', nextStatus: '��ʾ����',
        currentText: '����', currentStatus: '��ʾ����',
        monthNames: ['һ��', '����', '����', '����', '����', '����',
        '����', '����', '����', 'ʮ��', 'ʮһ��', 'ʮ����'],
        monthNamesShort: ['һ', '��', '��', '��', '��', '��',
        '��', '��', '��', 'ʮ', 'ʮһ', 'ʮ��'],
        monthStatus: 'ѡ���·�', yearStatus: 'ѡ�����',
        weekHeader: '��', weekStatus: '�����ܴ�',
        dayNames: ['������', '����һ', '���ڶ�', '������', '������', '������', '������'],
        dayNamesShort: ['����', '��һ', '�ܶ�', '����', '����', '����', '����'],
        dayNamesMin: ['��', 'һ', '��', '��', '��', '��', '��'],
        dayStatus: '���� DD Ϊһ����ʼ', dateStatus: 'ѡ�� m�� d��, DD',
        dateFormat: 'yy-mm-dd', firstDay: 1,
        initStatus: '��ѡ������', isRTL: false
    };
    $.datepicker.setDefaults($.datepicker.regional['zh-CN']);

    //���� Timepicker
	$.timepicker.regional['zh-CN'] = {
		timeOnlyTitle: 'ѡ��ʱ��',
		timeText: 'ʱ��',
		hourText: 'Сʱ',
		minuteText: '����',
		secondText: '����',
		millisecText: '΢��',
		timezoneText: 'ʱ��',
		currentText: '����ʱ��',
		closeText: '�ر�',
		timeFormat: 'hh:mm',
		amNames: ['AM', 'A'],
		pmNames: ['PM', 'P'],
		ampm: false
	};
	$.timepicker.setDefaults($.timepicker.regional['zh-CN']);
})(jQuery);
