(function($){
    $.fn.initRulePanel = function (options) {
        var settings = $.extend({}, options);
        var self = $(this), fn = this;
        this.addRule = function(e) {
            var data = $.extend({
                id: '',
                type: '',
                operator: '',
                promotion: {
                    type: '',
                    attribute: '',
                    value: ''
                }
            }, e);
            var template = self.find('[data-template="rule"]').clone()
                .removeClass('d-none')
                .addClass('rule-entity')
                .attr('data-template', '')
                .attr('id', 'rule' + (data.id ? data.id : self.find('.rule').length));
            $.each(settings.setup.rule.types, function(key) {
                $(template).find('[name="rule[type]"]').append('<option value="'+key+'" ' + (data.type === key ? 'selected' : '') +'>'+ this.label +'</option>');
                $.each(this.operators, function(key, label) {
                    $(template).find('[name="rule[operator]"]').append('<option value="'+key+'" '+ (data.operator === key ? 'selected' : '') +'>'+ label +'</option>');
                });
                template.find('[data-bind="addCondition"]').on('click', fn.addCondition);
                template.find('[data-bind="removeRule"]').on('click', fn.removeRule);
            });
            $(template).find('[name="rule[id]"]').val(data.id);
            $.each(settings.setup.promotion.types, function(key) {
                $(template).find('[name="promotion[type]"]').append('<option value="'+key+'" '+ (data.promotion.type === key ? 'selected' : '') +'>'+ this.label +'</option>');
                $.each(this.operators, function(key, label) {
                    $(template).find('[name="promotion[operator]"]').append('<option value="'+key+'" '+ (data.promotion.operator === key ? 'selected' : '') +'>'+ label +'</option>');
                });
                $.each(this.attributes, function(key, label) {
                    $(template).find('[name="promotion[attribute]"]').append('<option value="'+key+'" '+ (data.promotion.attribute === key ? 'selected' : '') +'>'+ label +'</option>');
                });
                $(template).find('[name="promotion[value]"]').val(data.promotion.value);
                $(template).find('[name="promotion[id]"]').val(data.promotion.id);
            });

            self.find('[data-container="rule"]').append(template);
        };

        this.addCondition = function (e) {
            var data = $.extend({
                id: '',
                type: '',
                attribute: '',
                operator: '',
                value: ''
            }, e);
            var container = $(e.target).closest('.condition').find('[data-container="condition"]');
            var template = container.find('[data-template="condition"]').clone()
                .removeClass('d-none')
                .addClass('condition-entity')
                .attr('data-template', '')
                .attr('id', 'condition' + (data.id ? data.id : self.find('.condition').length));
            $.each(settings.setup.condition.types, function(key) {
                $(template).find('[name="condition[type]"]').append('<option value="'+key+'" '+ (data.type === key ? 'selected' : '') +'>'+ this.label +'</option>');
                $.each(this.operators, function(key, label) {
                    $(template).find('[name="condition[operator]"]').append('<option value="'+key+'" '+ (data.operator === key ? 'selected' : '') +'>'+ label +'</option>');
                });
                $.each(this.attributes, function(key, label) {
                    $(template).find('[name="condition[attribute]"]').append('<option value="'+key+'" '+ (data.attribute === key ? 'selected' : '') +'>'+ label +'</option>');
                });
                $(template).find('[name="condition[value]"]').val(data.value);
                $(template).find('[name="condition[id]"]').val(data.id);

                template.find('[data-bind="removeCondition"]').on('click', fn.removeCondition);
            });
            container.append(template);
        };

        this.removeRule = function (e) {
            $(e.target).closest('.rule-entity').remove();
        };

        this.removeCondition = function (e) {
            $(e.target).closest('.condition-entity').remove();
        };

        function bind() {
            self.closest('form').on('submit', function (e) {
                var rules = [];
                $.each($(this).find('.rule-entity'), function () {
                    var rule = {};
                    rule.id = $(this).find('[name="rule[id]"]').val();
                    rule.type = $(this).find('[name="rule[type]"]').val();
                    rule.operator = $(this).find('[name="rule[operator]"]').val();
                    rule.promotion = {};
                    rule.promotion.id = $(this).find('[name="promotion[id]"]').val();
                    rule.promotion.type = $(this).find('[name="promotion[type]"]').val();
                    rule.promotion.attribute = $(this).find('[name="promotion[attribute]"]').val();
                    rule.promotion.value = $(this).find('[name="promotion[value]"]').val();
                    rule.conditions = [];
                    $.each($(this).find('.condition-entity'), function () {
                        var condition = {};
                        condition.id = $(this).find('[name="condition[id]"]').val();
                        condition.type = $(this).find('[name="condition[type]"]').val();
                        condition.attribute = $(this).find('[name="condition[attribute]"]').val();
                        condition.operator = $(this).find('[name="condition[operator]"]').val();
                        condition.value = $(this).find('[name="condition[value]"]').val();
                        rule.conditions.push(condition);
                    });
                    rules.push(rule);
                });
                $(this).find('[name*="[rules]"]').val(JSON.stringify(rules));
            });
            self.find('[data-bind="addRule"]').on('click', fn.addRule);
        }

        function render() {
            var rules = JSON.parse(self.find('[name*="[rules]"]').val());
            $.each(rules, function (k) {
                fn.addRule(rules[k]);
                $.each(rules[k]['conditions'], function (i) {
                    var condition = rules[k]['conditions'][i];
                    condition.target = $('#rule'+rules[k]['id']+ ' [data-bind="addCondition"]')[0];
                    fn.addCondition(condition);
                });
            });
        }

        function initialize() {
            render();
            bind();
        }

        initialize();
        return this;
    }
}(jQuery));