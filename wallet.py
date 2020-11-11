import subprocess
import json
import os
from constants import *
from dotenv import load_dotenv
from web3 import Web3
from pathlib import Path
from getpass import getpass
from eth_account import Account
from bit import wif_to_key
from bit import PrivateKeyTestnet
from bit.network import NetworkAPI
from web3.auto.gethdev import w3


load_dotenv()

# import mnemonic from env
mnemonic = os.getenv('Mnemonic_PRIVATEKEY')
# Coins private keys
eth_key = os.getenv('ETH_KEY')
btc_key = wif_to_key(os.getenv('BTC_KEY'))
eth_acc=Account.from_key(eth_key)
# create a function to hold coins
eth_acc = priv_key_to_account(ETH, coins[ETH][0]['privkey'])
btc_acc = priv_key_to_account(BTCTEST,btc_key)


#print(eth_acc.address)
#print(btc_acc)

def derive_wallets(coin):
    command  = f'./derive -g --mnemonic="{mnemonic}" --cols=path,address,privkey,pubkey --format=json --coin="{coin}" --numderive= 2'
    # command: is the command line to call subprocess
    # stdout: is standard output, which is the output the program runs to the operating system to print output
    p = subprocess.Popen(command, stdout=subprocess.PIPE, shell=True)
    # capture the output & the error from the process
    (output, err) = p.communicate()
    # to let the program wait to capture the process
    p_status = p.wait()
  
    keys = json.loads(output)
    return keys

# connect to local ETH/ geth
w3 = Web3(Web3.HTTPProvider("http://127.0.0.1:8545"))
INDEX = 0
coins = {ETH: derive_wallets(ETH), BTCTEST: derive_wallets(BTCTEST)}

#print(w3.eth.getBalance("0x305fa4d6e4b1317545cd5f5c3586ebc01d12549b"))

# create a function that convert the privkey string in a child key to an account object.
def priv_key_to_account(coin,priv_key):
    print(coin)
    print(priv_key)
    if coin == ETH:
        return Account.privateKeyToAccount(priv_key)
    elif coin == BTCTEST:
        return PrivateKeyTestnet(priv_key)

def create_tx(coin,account, recipient, amount):
    if coin == ETH: 
        gasEstimate = w3.eth.estimateGas(
            {"from":eth_acc.address, "to":recipient, "value": amount}
        )
        return { 
            "from": eth_acc.address,
            "to": recipient,
            "value": amount,
            "gasPrice": w3.eth.gasPrice,
            "gas": gasEstimate,
            "nonce": w3.eth.getTransactionCount(eth_acc.address)
        }
    
    elif coin == BTCTEST:
        return PrivateKeyTestnet.prepare_transaction(account.address, [(recipient, amount, BTC)])

# create a function to send txn
def send_txn(coin,account,recipient, amount):
    txn = create_tx(coin, account, recipient, amount)
    if coin == ETH:
        signed_txn = eth_acc.sign_transaction(txn)
        result = w3.eth.sendRawTransaction(signed_txn.rawTransaction)
        print(result.hex())
        return result.hex()
    elif coin == BTCTEST:
        tx_btctest = create_tx(coin, account, recipient, amount)
        signed_txn = account.sign_transaction(txn)
        print(signed_txn)
        return NetworkAPI.broadcast_tx_testnet(signed_txn)

create_tx(BTCTEST,btc_acc,"mzZvByXUY9PtPymNGW119PJpUidbjoaMBF", 0.1)
send_txn(BTCTEST,btc_acc," mvu2KnbpdXBmSFqPau6DWwBnMS8U67Gvcz", 0.1)
create_tx(ETH,eth_acc,"0x78A47Ef95A669e53FFDBa941ceCfD71d459633F5", 1000)
send_txn(ETH, eth_acc,"0x1c431Dc9B65A1bDC78c4a7ecc9eC05Aa292631F6", 1000)

print(eth_key.get_transactions())

print(btc_key.get_transactions())