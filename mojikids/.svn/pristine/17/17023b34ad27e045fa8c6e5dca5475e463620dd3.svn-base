(function()
{
    var run_status = 'DEV' == 'DEV' ? false : true ;

    var version = new Date().getTime();

    require.resourceMap(__RESOURCE_MAP__);

	console.log(require.__RESOURCES_MAP_OBJ);

    var __rmap = require.__RESOURCES_MAP_OBJ;
    var __global_list_map = [];

    for(var key in __rmap['res'])
    {
        if(__rmap['res'][key] && __rmap['res'][key]['uri'] &&
		( /wap-kids:global(.*)/.test(key) || /wap-kids:widget(.*)/.test(key) || /wap-kids:modules\/widget(.*)/.test(key)  ) )
        {
            __global_list_map.push(key);
        }

    }

    __yue_ls__.loader(__rmap,__global_list_map,run_status,version,'mojikids');

})();
