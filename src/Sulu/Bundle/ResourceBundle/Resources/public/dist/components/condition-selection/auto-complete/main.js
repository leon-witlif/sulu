define(["underscore"],function(a){"use strict";var b={options:{value:null,operator:null,parameters:{}}};return{defaults:b,tagToId:{},initialize:function(){this.$container=$("<div/>"),this.instanceName=a.uniqueId("condition-tags-"),this.value=this.options.value,this.$el.append(this.$container),this.$el.data("value",this.options.value),this.sandbox.start([{name:"auto-complete@husky",options:{el:this.$container,instanceName:this.instanceName,items:this.data,prefetchUrl:this.options.parameters.prefetchUrl,remoteUrl:this.options.parameters.remoteUrl,getParameter:this.options.parameters.searchParameter||"search",resultKey:this.options.parameters.resultKey,valueKey:this.options.parameters.valueKey}}]),this.sandbox.on("husky.auto-complete."+this.instanceName+".select",function(a){this.value=a.id,this.$el.data("value",this.value)}.bind(this))},loadComponentData:function(){var a=$.Deferred();return a.resolve([]),a.promise()}}});