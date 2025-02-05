(function($){
    var ProjetosVinculados = {
        init: function(){
            this.$container = $('table.scroll300 tbody');
            this.$trs = $('table.scroll300 tbody tr.nao-incluido');
            this.$qtdIndexTrs = this.$trs.length - 1;
            this.indexTrAnterior = this.$qtdIndexTrs;
            this.indexTrAtual = this.indexTrProximo = 0;
            this.events();
        },
        events: function(){
            $('#btn-prev-projeto').click(this.handleClickBtnAnterior);
            $('#btn-next-projeto').click(this.handleClickBtnProximo);
        },
        handleClickBtnAnterior: function() {
            ProjetosVinculados.scroll(ProjetosVinculados.indexTrAnterior);
        },
        handleClickBtnProximo: function() {
            ProjetosVinculados.scroll(ProjetosVinculados.indexTrProximo);
        },
        getIndexPrev: function() {
            var prev = ProjetosVinculados.indexTrAtual - 1;
            if(prev < 0) {
                prev = ProjetosVinculados.$qtdIndexTrs;
            }   
            return prev;
        },
        getIndexNext: function() {
            var next = ProjetosVinculados.indexTrAtual + 1;
            if(next > ProjetosVinculados.$qtdIndexTrs) {
                next = 0;
            }   
            return next;
        },
        scroll: function(indexTr) {
            ProjetosVinculados.$container.animate({
                scrollTop: $(ProjetosVinculados.$trs[indexTr]).offset().top - ProjetosVinculados.$container.offset().top + ProjetosVinculados.$container.scrollTop()
            }, {
                duration: 250, 
                complete: function() {
                    ProjetosVinculados.indexTrAtual = indexTr;
                    ProjetosVinculados.highlight();
                    ProjetosVinculados.indexTrAnterior = ProjetosVinculados.getIndexPrev();
                    ProjetosVinculados.indexTrProximo = ProjetosVinculados.getIndexNext();
            }});
        },
        highlight: function() {
            $(ProjetosVinculados.$trs[ProjetosVinculados.indexTrAtual]).toggle("highlight");
            $(ProjetosVinculados.$trs[ProjetosVinculados.indexTrAtual]).toggle("highlight");
        }
    };
    $(document).ready(function(){
        ProjetosVinculados.init();
    });
})(jQuery);