import $ from 'jquery';

/*========= Acordeón de preguntas frecuentes =========*/
export default function initFaq() {
    const $questions = $('.fqas-partial-4947e5 .question');
    if (!$questions.length) return;

    const toggle = (question) => {
        const $question = $(question);
        const $answer = $question.next('.answer');
        const isOpen = $question.hasClass('active');

        $questions.removeClass('active').attr('aria-expanded', 'false');
        $('.fqas-partial-4947e5 .answer').css('display', 'none');

        if (!isOpen) {
            $question.addClass('active').attr('aria-expanded', 'true');
            $answer.css('display', 'block');
        }
    };

    $questions.on('click', function() {
        toggle(this);
    }).on('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggle(this);
        }
    });
}
