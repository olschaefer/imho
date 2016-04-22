local m, err = ngx.re.match(ngx.var.uri, "^/\\d\\d/(?<bucket>\\d+)/", "ajo");
if m then
    local bucket = tonumber(m["bucket"]);
    if bucket >= 0 and bucket <= 49999 then
        return 'storage_node_1';
    elseif bucket >= 50000 and bucket <= 99999 then
        return 'storage_node_2';
    end
else
    return '';
end