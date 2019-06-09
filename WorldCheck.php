<?php
	
	/**
	 * @name WorldCheck
	 * @main PJZ9n\WorldCheck\Main
	 * @version 1.0.0
	 * @api 3.0.0
	 * @description World Check Plugin
	 * @author PJZ9n
	 */
	
	namespace PJZ9n\WorldCheck {
		
		use pocketmine\command\Command;
		use pocketmine\command\CommandSender;
		use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
		use pocketmine\Player;
		use pocketmine\plugin\PluginBase;
		
		class Main extends PluginBase {
			
			public function onEnable() {
				$this->getServer()->getCommandMap()->register("WorldCheck", new worldCheckCommand($this));
			}
			
		}
		
		class worldCheckCommand extends Command {
			
			/** @var Main */
			private $owner;
			
			public function __construct(Main $owner) {
				parent::__construct(
					"worldcheck",
					"ワールド名の確認"
				);
				$this->owner = $owner;
			}
			
			public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
				if (!$sender instanceof Player) {
					$sender->sendMessage("プレイヤーのみ使用可能");
					return true;
				}
				$buttons = [];
				foreach ($this->owner->getServer()->getLevels() as $level) {
					$color = ($level->getName() !== $level->getFolderName()) ? "§c" : "§1";
					$buttons[] = [
						"text" => "{$color}{$level->getName()} §e/ {$color}{$level->getFolderName()}",
					];
				}
				
				$pk = new ModalFormRequestPacket();
				$pk->formId = 0;
				$pk->formData = json_encode(
					[
						"type" => "form",
						"title" => "ワールド名の確認",
						"content" => "§bLevel名 §e/ §bFolder名",
						"buttons" => $buttons,
					]
				);
				$sender->sendDataPacket($pk);
				return true;
			}
			
		}
		
	}