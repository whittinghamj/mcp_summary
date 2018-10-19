_IP=$(hostname -I) || true
if [ "$_IP" ]; then
  printf "%s\n" "$_IP"
fi