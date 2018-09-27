(function($){
    $.fn.initRulePanel = function (options) {
        var settings = $.extend({}, options);
        var self = $(this), fn = this;
        this.addRule = function() {
            var template = self.find('[data-template="rule"]').clone()
                .removeClass('d-none')
                .addClass('rule-entity')
                .attr('data-template', '')
                .attr('id', 'rule' + self.find('.rule').length);
            $.each(settings.setup.rule.types, function(key) {
                $(template).find('[name="rule[type]"]').append('<option value="'+key+'">'+ this.label +'</option>');
                $.each(this.operators, function(key, label) {
                    $(template).find('[name="rule[operator]"]').append('<option value="'+key+'">'+ label +'</option>');
                });
                template.find('[data-bind="addCondition"]').on('click', fn.addCondition);
                template.find('[data-bind="removeRule"]').on('click', fn.removeRule);
            });
            $.each(settings.setup.promotion.types, function(key) {
                $(template).find('[name="promotion[type]"]').append('<option value="'+key+'">'+ this.label +'</option>');
                $.each(this.operators, function(key, label) {
                    $(template).find('[name="promotion[operator]"]').append('<option value="'+key+'">'+ label +'</option>');
                });
                $.each(this.attributes, function(key, label) {
                    $(template).find('[name="promotion[attribute]"]').append('<option value="'+key+'">'+ label +'</option>');
                });
            });

            self.find('[data-container="rule"]').append(template);
        };

        this.addCondition = function (e) {
            var container = $(e.target).parent().find('[data-container="condition"]');
            var template = container.find('[data-template="condition"]').clone()
                .removeClass('d-none')
                .addClass('condition-entity')
                .attr('data-template', '')
                .attr('id', 'condition' + self.find('.condition').length);
            $.each(settings.setup.condition.types, function(key) {
                $(template).find('[name="condition[type]"]').append('<option value="'+key+'">'+ this.label +'</option>');
                $.each(this.operators, function(key, label) {
                    $(template).find('[name="condition[operator]"]').append('<option value="'+key+'">'+ label +'</option>');
                });
                $.each(this.attributes, function(key, label) {
                    $(template).find('[name="condition[attribute]"]').append('<option value="'+key+'">'+ label +'</option>');
                });
                template.find('[data-bind="removeCondition"]').on('click', fn.removeCondition);
            });
            container.append(template);
        };

        this.removeRule = function (e) {
            $(e.target).closest('.rule').remove();
        };

        this.removeCondition = function (e) {
            $(e.target).closest('.condition').remove();
        };

        function bind() {
            self.closest('form').on('submit', function (e) {
                var rules = [];
                $.each($(this).find('.rule-entity'), function () {
                    var rule = {};
                    rule.type = $(this).find('[name="rule[type]"]').val();
                    rule.operator = $(this).find('[name="rule[operator]"]').val();
                    rule.promotion = {};
                    rule.promotion.type = $(this).find('[name="promotion[type]"]').val();
                    rule.promotion.attribute = $(this).find('[name="promotion[attribute]"]').val();
                    rule.promotion.value = $(this).find('[name="promotion[value]"]').val();
                    rule.condition = [];
                    $.each($(this).find('.condition-entity'), function () {
                        var condition = {};
                        condition.type = $(this).find('[name="condition[type]"]').val();
                        condition.attribute = $(this).find('[name="condition[attribute]"]').val();
                        condition.operator = $(this).find('[name="condition[operator]"]').val();
                        condition.value = $(this).find('[name="condition[value]"]').val();
                        rule.condition.push(condition);
                    });
                    rules.push(rule);
                });
                $(this).find('[name*="[rule]"]').val(JSON.stringify(rules));
            });
            self.find('[data-bind="addRule"]').on('click', fn.addRule);
        }

        function initialize() {
            bind();
        }

        initialize();
        return this;
    }
}(jQuery));